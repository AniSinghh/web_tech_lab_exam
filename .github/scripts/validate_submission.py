#!/usr/bin/env python3
"""Validate Web Technology Lab exam submissions."""

from __future__ import annotations

import os
import re
import subprocess
import sys
from pathlib import Path


ROOT = Path(__file__).resolve().parents[2]
PROTECTED_PATHS = {
    ".github/workflows/validate.yml",
    ".github/scripts/validate_submission.py",
}
IGNORED_DIRS = {".git", "vendor", "node_modules"}


def fail(message: str) -> None:
    print(f"ERROR: {message}")
    sys.exit(1)


def run(command: list[str], check: bool = False) -> subprocess.CompletedProcess[str]:
    return subprocess.run(
        command,
        cwd=ROOT,
        text=True,
        stdout=subprocess.PIPE,
        stderr=subprocess.STDOUT,
        check=check,
    )


def changed_files() -> set[str]:
    allow = os.environ.get("ALLOW_PROTECTED_CHANGES", "false").lower() == "true"
    if allow:
        return set()

    event = os.environ.get("GITHUB_EVENT_NAME", "")
    base_ref = os.environ.get("GITHUB_BASE_REF", "")
    before = os.environ.get("GITHUB_EVENT_BEFORE", "")
    sha = os.environ.get("GITHUB_SHA", "HEAD")

    if event == "pull_request" and base_ref:
        run(["git", "fetch", "origin", base_ref, "--depth=1"])
        diff_range = f"origin/{base_ref}...HEAD"
    elif before and not re.fullmatch(r"0+", before):
        diff_range = f"{before}..{sha}"
    else:
        return set()

    result = run(["git", "diff", "--name-only", diff_range])
    if result.returncode != 0:
        print(result.stdout)
        return set()
    return {line.strip() for line in result.stdout.splitlines() if line.strip()}


def enforce_protected_files() -> None:
    touched = changed_files() & PROTECTED_PATHS
    if touched:
        fail(
            "Do not modify validation workflow files: "
            + ", ".join(sorted(touched))
        )


def php_files() -> list[Path]:
    files: list[Path] = []
    for path in ROOT.rglob("*.php"):
        if any(part in IGNORED_DIRS for part in path.relative_to(ROOT).parts):
            continue
        files.append(path)
    return sorted(files)


def lint_php(files: list[Path]) -> None:
    for path in files:
        result = run(["php", "-l", str(path.relative_to(ROOT))])
        if result.returncode != 0:
            print(result.stdout)
            fail(f"PHP syntax check failed for {path.relative_to(ROOT)}")


def combined_source(files: list[Path]) -> str:
    content = []
    for path in files:
        content.append(f"\n/* file: {path.relative_to(ROOT)} */\n")
        content.append(path.read_text(encoding="utf-8", errors="ignore"))
    return "\n".join(content).lower()


def has_any(text: str, patterns: list[str]) -> bool:
    return any(re.search(pattern, text, re.I | re.S) for pattern in patterns)


def require(checks: list[tuple[bool, str]]) -> list[str]:
    return [message for ok, message in checks if not ok]


def validate_set_a(text: str) -> list[str]:
    return require(
        [
            (has_any(text, [r"<form", r"\$_post", r"\$_get"]), "use an HTML form and PHP request handling"),
            (has_any(text, [r"name", r"student"]), "handle the student name"),
            (has_any(text, [r"roll"]), "handle the roll number"),
            (len(re.findall(r"mark|english|mathematics|math|computer", text)) >= 3, "handle three subject marks"),
            (has_any(text, [r"total", r"\+"]), "calculate total marks"),
            (has_any(text, [r"percent|percentage|average|/"]), "calculate percentage or average"),
            (has_any(text, [r"pass", r"fail", r"grade"]), "display pass/fail or grade"),
            (has_any(text, [r"<table"]), "display the result in an HTML table"),
        ]
    )


def validate_set_b(text: str) -> list[str]:
    return require(
        [
            (has_any(text, [r"\[[^\]]*,[^\]]*,[^\]]*,[^\]]*,[^\]]*"]), "define an array of numbers or products"),
            (has_any(text, [r"foreach", r"for\s*\(", r"while\s*\("]), "use a loop to display values"),
            (has_any(text, [r"max\s*\(", r"largest", r">\s*\$"]), "find the largest number"),
            (has_any(text, [r"min\s*\(", r"smallest", r"<\s*\$"]), "find the smallest number"),
            (has_any(text, [r"array_sum\s*\(", r"sum", r"total"]), "calculate sum or total"),
            (has_any(text, [r"average", r"avg", r"/\s*count\s*\("]), "calculate average"),
            (has_any(text, [r"=>"]), "use an associative array for products and prices"),
            (has_any(text, [r"discount", r"0\.10|10\s*/\s*100|10%"]), "calculate the 10 percent discount"),
            (has_any(text, [r"<table"]), "display products in an HTML table"),
        ]
    )


def validate_set_c(text: str) -> list[str]:
    return require(
        [
            (has_any(text, [r"<form"]), "create an HTML form"),
            (has_any(text, [r"method\s*=\s*[\"']?post", r"\$_post"]), "process the form using POST"),
            (all(word in text for word in ["name", "email", "age", "city"]), "handle name, email, age, and city"),
            (has_any(text, [r"roll"]), "handle roll number for registration"),
            (has_any(text, [r"department"]), "handle department"),
            (has_any(text, [r"gender"]), "handle gender"),
            (has_any(text, [r"year"]), "handle year of study"),
            (has_any(text, [r"empty\s*\(", r"required", r"==\s*[\"']{2}", r"error"]), "validate required fields"),
            (has_any(text, [r"confirmation", r"submitted", r"registration"]), "display a confirmation or submitted details page"),
        ]
    )


def validate_set_d(text: str) -> list[str]:
    return require(
        [
            (has_any(text, [r"function\s+calculatebill\s*\("]), "define calculateBill()"),
            (has_any(text, [r"quantity", r"qty"]), "accept or display quantity"),
            (has_any(text, [r"price"]), "accept or display price"),
            (has_any(text, [r"return\s+.*\*", r"\*\s*\$"]), "calculate bill using quantity and price"),
            (has_any(text, [r"<form"]), "create an HTML form"),
            (all(item in text for item in ["burger", "pizza", "pasta", "sandwich"]), "include the restaurant menu items"),
            (has_any(text, [r"gst", r"0\.05|5\s*/\s*100|5%"]), "calculate 5 percent GST"),
            (has_any(text, [r"final", r"payable", r"grand"]), "display final payable amount"),
        ]
    )


def validate_set_e(text: str) -> list[str]:
    return require(
        [
            (has_any(text, [r"<form"]), "create an HTML form"),
            (has_any(text, [r"positive", r"negative", r"zero"]), "check positive, negative, or zero"),
            (has_any(text, [r"%\s*2", r"even", r"odd"]), "check even or odd"),
            (has_any(text, [r"employee"]), "handle employee name"),
            (has_any(text, [r"basic"]), "handle basic salary"),
            (has_any(text, [r"hra"]), "calculate HRA"),
            (has_any(text, [r"\bda\b|dearness"]), "calculate DA"),
            (has_any(text, [r"gross"]), "calculate gross salary"),
            (has_any(text, [r"<table"]), "display salary details in a formatted table"),
        ]
    )


def validate_set_f(text: str) -> list[str]:
    return require(
        [
            (has_any(text, [r"session_start\s*\("]), "start a PHP session"),
            (has_any(text, [r"\$_session"]), "store or read values from the session"),
            (has_any(text, [r"login", r"username", r"password"]), "create login handling"),
            (has_any(text, [r"header\s*\(", r"location:"]), "redirect after login"),
            (has_any(text, [r"welcome"]), "display a welcome page/message"),
            (has_any(text, [r"session_destroy\s*\(", r"logout"]), "provide logout that destroys the session"),
            (has_any(text, [r"attendance"]), "display attendance percentage"),
            (has_any(text, [r"eligible", r"not eligible", r"75"]), "display examination eligibility based on 75 percent attendance"),
        ]
    )


VALIDATORS = {
    "A": validate_set_a,
    "B": validate_set_b,
    "C": validate_set_c,
    "D": validate_set_d,
    "E": validate_set_e,
    "F": validate_set_f,
}


def selected_set(text: str) -> str:
    declared = os.environ.get("QUESTION_SET", "").strip().upper()
    set_file = ROOT / "SET.txt"
    if not declared and set_file.exists():
        declared = set_file.read_text(encoding="utf-8", errors="ignore").strip().upper()
    if declared in VALIDATORS:
        return declared
    if declared:
        fail("SET.txt or QUESTION_SET must contain one of A, B, C, D, E, or F")

    scores: dict[str, int] = {}
    for set_name, validator in VALIDATORS.items():
        scores[set_name] = len(validator(text))
    best = min(scores, key=scores.get)
    print(f"No SET.txt found. Inferred Set {best} from submission content.")
    return best


def main() -> None:
    enforce_protected_files()

    files = php_files()
    if not files:
        fail("No PHP files found. Add your solution files before pushing.")

    lint_php(files)
    text = combined_source(files)
    set_name = selected_set(text)
    errors = VALIDATORS[set_name](text)

    print(f"Detected/selected question set: {set_name}")
    print("PHP files checked:")
    for path in files:
        print(f"- {path.relative_to(ROOT)}")

    if errors:
        print("\nMissing requirements:")
        for error in errors:
            print(f"- {error}")
        fail(f"Submission does not satisfy Set {set_name}")

    print(f"Submission satisfies the automated checks for Set {set_name}.")


if __name__ == "__main__":
    main()
