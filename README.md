LoanInvest
====
The project uses Symfony 5.4.4 as the framework and uses sqlite for database.

Install
--------
# Install all required packages
1. composer install

# Creating the Database Tables/Schema
2.php bin/console make:migration
# In case the database not generate 
php bin/console doctrine:migrations:migrate

Database structure
--------
#Investor:

id, name, wallet_amount

#Loan:
id, invest_amount, start_date, end_date, investor_id, tranche_id

#Tranche:
id, name, interest_rate, current_amount, maximum_allowance