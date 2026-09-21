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
    "README.md",
    "SET.txt",
}
PROTECTED_PREFIXES = (".github/",)
IGNORED_DIRS = {".git", "vendor", "node_modules"}
ROOT_ALLOWED_FILES = {".gitignore", "README.md", "SET.txt"}
ROOT_ALLOWED_DIRS = {".github"}
ALLOWED_ROLL_FOLDERS = {
    "PG_04_MCA_2025_001",
    "PG_04_MCA_2025_002",
    "PG_04_MCA_2025_003",
    "PG_04_MCA_2025_004",
    "PG_04_MCA_2025_005",
    "PG_04_MCA_2025_007",
    "PG_04_MCA_2025_008",
    "PG_04_MCA_2025_009",
    "PG_04_MCA_2025_011",
    "PG_04_MCA_2025_013",
    "PG_04_MCA_2025_014",
    "PG_04_MCA_2025_016",
    "PG_04_MCA_2025_017",
    "PG_04_MCA_2025_018",
    "PG_04_MCA_2025_019",
    "PG_04_MCA_2025_020",
    "PG_04_MCA_2025_021",
    "PG_04_MCA_2025_022",
    "PG_04_MCA_2025_023",
    "PG_04_MCA_2025_025",
    "PG_04_MCA_2025_026",
    "PG_04_MCA_2025_027",
    "PG_04_MCA_2025_028",
    "PG_04_MCA_2025_031",
    "PG_04_MCA_2025_033",
    "PG_04_MCA_2025_034",
    "PG_04_MCA_2025_035",
    "PG_04_MCA_2025_036",
    "PG_04_MCA_2025_037",
    "PG_04_MCA_2025_040",
    "PG_04_MCA_2025_041",
    "PG_04_MCA_2025_042",
    "PG_04_MCA_2025_043",
    "PG_04_MCA_2025_044",
    "PG_04_MCA_2025_045",
    "PG_04_MCA_2025_046",
    "PG_04_MCA_2025_047",
    "PG_04_MCA_2025_048",
    "PG_04_MCA_2025_049",
    "PG_04_MCA_2025_050",
    "PG_04_MCA_2025_051",
    "PG_04_MCA_2025_052",
    "PG_04_MCA_2025_053",
    "PG_04_MCA_2025_054",
    "PG_04_MCA_2025_055",
    "PG_04_MCA_2025_056",
    "PG_04_MCA_2025_057",
    "PG_04_MCA_2025_058",
    "PG_04_MCA_2025_059",
    "PG_04_MCA_2025_060",
}


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


def changed_file_statuses() -> list[tuple[str, str]]:
    allow = os.environ.get("ALLOW_PROTECTED_CHANGES", "false").lower() == "true"
    if allow:
        return []

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
        return []

    result = run(["git", "diff", "--name-status", diff_range])
    if result.returncode != 0:
        print(result.stdout)
        return []

    statuses: list[tuple[str, str]] = []
    for line in result.stdout.splitlines():
        parts = line.split("\t")
        if len(parts) >= 2:
            statuses.append((parts[0], parts[-1]))
    return statuses


def enforce_protected_files() -> None:
    changed = changed_files()
    touched = (changed & PROTECTED_PATHS) | {
        path for path in changed if path.startswith(PROTECTED_PREFIXES)
    }
    if touched:
        fail(
            "Do not modify protected exam files: "
            + ", ".join(sorted(touched))
        )

    deleted = {path for status, path in changed_file_statuses() if "D" in status}
    deleted_protected = (deleted & PROTECTED_PATHS) | {
        path for path in deleted if path.startswith(PROTECTED_PREFIXES)
    }
    if deleted_protected:
        fail(
            "Do not delete protected exam files: "
            + ", ".join(sorted(deleted_protected))
        )

    for protected in PROTECTED_PATHS:
        if not (ROOT / protected).exists():
            fail(f"Protected exam file is missing: {protected}")


def enforce_repository_shape() -> None:
    bad_entries: list[str] = []
    result = run(["git", "ls-files"])
    if result.returncode != 0:
        print(result.stdout)
        fail("Unable to inspect tracked repository files")

    for raw_path in result.stdout.splitlines():
        if not raw_path:
            continue
        first = raw_path.split("/", 1)[0]
        if "/" not in raw_path and first not in ROOT_ALLOWED_FILES:
            bad_entries.append(raw_path)
        if "/" in raw_path and first not in ROOT_ALLOWED_DIRS and first not in ALLOWED_ROLL_FOLDERS:
            bad_entries.append(first + "/")

    if bad_entries:
        fail(
            "Only protected root files and allowed roll-number folders are permitted. "
            "Invalid root entries: " + ", ".join(sorted(set(bad_entries)))
        )


def roll_folder_for(path: Path) -> str | None:
    relative = path.relative_to(ROOT)
    if not relative.parts:
        return None
    first = relative.parts[0]
    if first in ALLOWED_ROLL_FOLDERS:
        return first
    return None


def changed_roll_folders() -> set[str]:
    folders: set[str] = set()
    changed = changed_files()
    for raw_path in changed:
        first = raw_path.split("/", 1)[0]
        if first in ALLOWED_ROLL_FOLDERS:
            folders.add(first)
    return folders


def php_files(folder: str | None = None) -> list[Path]:
    files: list[Path] = []
    search_root = ROOT / folder if folder else ROOT
    for path in search_root.rglob("*.php"):
        if any(part in IGNORED_DIRS for part in path.relative_to(ROOT).parts):
            continue
        files.append(path)
    return sorted(files)


def enforce_php_locations(files: list[Path]) -> None:
    invalid = [str(path.relative_to(ROOT)) for path in files if roll_folder_for(path) is None]
    if invalid:
        fail(
            "All PHP files must be inside an allowed roll-number folder. "
            "Invalid PHP file locations: " + ", ".join(invalid)
        )


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


def selected_set(text: str, folder: str) -> str:
    declared = os.environ.get("QUESTION_SET", "").strip().upper()
    set_file = ROOT / folder / "SET.txt"
    if not declared and set_file.exists():
        declared = set_file.read_text(encoding="utf-8", errors="ignore").strip().upper()
    root_set_file = ROOT / "SET.txt"
    if not declared and root_set_file.exists():
        declared = root_set_file.read_text(encoding="utf-8", errors="ignore").strip().upper()
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
    enforce_repository_shape()

    all_php_files = php_files()
    enforce_php_locations(all_php_files)

    folders = changed_roll_folders()
    if not folders:
        folders = {roll_folder_for(path) for path in all_php_files if roll_folder_for(path)}
    folders = {folder for folder in folders if folder}

    if not folders:
        fail(
            "Create a folder using your roll number with underscores, for example "
            "PG_04_MCA_2025_001, and place your PHP code inside it."
        )

    for folder in sorted(folders):
        files = php_files(folder)
        if not files:
            fail(f"No PHP files found inside {folder}. Add your solution files there before pushing.")

        lint_php(files)
        text = combined_source(files)
        set_name = selected_set(text, folder)
        errors = VALIDATORS[set_name](text)

        print(f"Roll folder checked: {folder}")
        print(f"Detected/selected question set: {set_name}")
        print("PHP files checked:")
        for path in files:
            print(f"- {path.relative_to(ROOT)}")

        if errors:
            print("\nMissing requirements:")
            for error in errors:
                print(f"- {error}")
            fail(f"Submission in {folder} does not satisfy Set {set_name}")

    if not all_php_files:
        fail("No PHP files found. Add your solution files before pushing.")

    print("Submission satisfies the automated checks.")


if __name__ == "__main__":
    main()
