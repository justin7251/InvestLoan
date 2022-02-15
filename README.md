LoanInvest
====
The project uses Symfony 5.4.4 as the framework and uses sqlite for database.


Database structure
--------
Investor:

id, name, wallet_amount

Loan:
id, invest_amount, start_date, end_date, investor_id, tranche_id

Tranche:
id, name, interest_rate, current_amount, maximum_allowance