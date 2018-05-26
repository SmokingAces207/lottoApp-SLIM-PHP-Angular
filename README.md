# lottoApp
Simple Lottery System Application Spec

The ticket

A ticket will consist of N lines, each line consisting of 3 numbers.
These numbers will be either 0, 1 or 2.


Logical flow of verifying ticket numbers

If totalValue is 2 
then result is 10
Else if number1 and number2 is equal and number1 and number3 is equal 
then result is 5
Else if number1 and number2 are not equal and number1 and number3 are not equal 
then result is 1
Else 
then result is 0


Interface

Generate a ticket
Ask the user how many lines they wish to add to ticket
Create a new ticket with a new ID
Randomly generate 3 numbers per line requested - These numbers being between 0 and 2 inclusive

Ticket list
If a ticket is not used it will be selectable from this menu
Once selected the user can request additional lines to be added
The user will be prompt for the number of lines to add
This will update the existing ticket with new lines of number between 0 and 2 inclusive
If a ticket has been already verified then the user will only be able to check previous results

Verify status of tickets
Here the user will be able to select a created ticket
The ticket will then be processed
The program will calculate the result of each individual line
Then add that result to the end of the line
Then the full ticket will be returned with the results for each line
At this point the user will not be able to request additional lines for this ticket




URL Routes

/ticket POST Create a ticket
/ticket GET Return list of tickets
/ticket/{id} GET Get individual ticket
/ticket/{id} PUT Amend ticket lines
/status/{id} PUT Retrieve status of ticket


Database

Ticket : 
ID , Lines

Line :
ID, TicketID, Num1, Num2, Num3, Result


Complete Stack Overview

HTML5
CSS3
Bootstrap
Javascript
AngularJs
PHP
Slim
PDO
MySQL
PhpMyAdmin
Composer
