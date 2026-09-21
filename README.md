# Web Technology Lab Mid Term Exam

Subject: Web Technology Lab  
Course Code: CSE22964  
Department: MCA  
Time: 2 Hours  
Total Marks: 20

## Submission Rules

1. Submit the solution for any one set from Set A to Set F.
2. Add a `SET.txt` file in the repository root containing only one letter: `A`, `B`, `C`, `D`, `E`, or `F`.
3. Keep all PHP solution files in the repository. You may use one file or multiple files.
4. Do not edit files inside `.github/`. The GitHub Actions workflow validates submissions and rejects workflow or validator changes.
5. A successful push means the action detected the submitted set, linted the PHP files, and found the required features for that set.

## Set A

### Q1. Basic - 10 Marks

Create a PHP program that accepts a student's name, roll number, and marks in three subjects through an HTML form. The PHP program should:

- Calculate the total marks and percentage.
- Display the student's details, total, and percentage.
- Display "Pass" if the percentage is 40 or above; otherwise display "Fail".

### Q2. Scenario Based - 10 Marks

A college wants to calculate the final result of a student.

Create an HTML form that accepts the student's name and marks in English, Mathematics, and Computer Science. Write PHP code to:

- Calculate the average marks.
- Assign a grade according to the following criteria:

| Average Marks | Grade |
| --- | --- |
| 80-100 | A |
| 60-79 | B |
| 40-59 | C |
| Below 40 | F |

- Display the student's result in a properly formatted HTML table.

## Set B

### Q1. Basic - 10 Marks

Create a PHP program containing an array of 10 numbers. The program should:

- Display all the numbers using a loop.
- Find and display the largest number.
- Find and display the smallest number.
- Calculate and display the sum and average of the numbers.

### Q2. Scenario Based - 10 Marks

You are developing a simple shopping cart for an online store.

Create a PHP program containing an associative array of product names and their prices. Display all products in an HTML table and calculate:

- Total price of all products.
- 10% discount if the total is above Rs. 2,000.
- Final payable amount.

## Set C

### Q1. Basic - 10 Marks

Create an HTML page containing a form that accepts name, email, age, and city. Write a PHP program to receive the form data using the POST method and display the submitted information on a new webpage.

The program should also check that none of the fields are empty.

### Q2. Scenario Based - 10 Marks

A university has created an online student registration form.

Create an HTML form containing:

- Student Name
- Roll Number
- Email
- Department
- Gender
- Year of Study

Write PHP code to process the form using POST and display a formatted registration confirmation page containing all submitted details. The program should display an error message if any required field is empty.

## Set D

### Q1. Basic - 10 Marks

Create a PHP program that defines a function named `calculateBill()` which accepts quantity and price as parameters and returns the total bill amount.

Create an HTML form through which the user can enter the quantity and price, call the function, and display the result.

### Q2. Scenario Based - 10 Marks

A restaurant wants a simple food billing system.

Create a PHP program with the following menu:

- Burger - Rs. 120
- Pizza - Rs. 250
- Pasta - Rs. 180
- Sandwich - Rs. 100

Create a form where the customer selects an item and enters its quantity. Use a PHP function to calculate the bill and display:

- Selected item
- Quantity
- Price
- Total amount
- 5% GST
- Final payable amount.

## Set E

### Q1. Basic - 10 Marks

Create a PHP program that accepts a number through an HTML form and determines whether the number is:

- Positive, negative, or zero.
- Even or odd.

Display the results on the webpage.

### Q2. Scenario Based - 10 Marks

A company wants to calculate the monthly salary of an employee.

Create an HTML form that accepts:

- Employee Name
- Basic Salary
- HRA percentage
- DA percentage

Write PHP code to calculate:

- HRA = Basic Salary x HRA%
- DA = Basic Salary x DA%
- Gross Salary = Basic Salary + HRA + DA

Display the employee's salary details in a formatted table.

## Set F

### Q1. Basic - 10 Marks

Create a PHP program using a session that allows a user to enter their name on a login page. After submission:

1. Store the name in a PHP session.
2. Redirect the user to a welcome page.
3. Display "Welcome, &lt;name&gt;".
4. Provide a logout option that destroys the session.

### Q2. Scenario Based - 10 Marks

You are developing a simple student attendance portal.

Create a login page that accepts a username and password. Use PHP to verify the credentials against predefined values.

After successful login:

- Start a session.
- Display the student's name and a simple attendance percentage.
- Display "Eligible for Examination" if attendance is 75% or above.
- Otherwise display "Not Eligible for Examination".
- Provide a logout option that destroys the session.

