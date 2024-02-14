<div>
    <h1>Transaction Details</h1>
    <p>Transaction ID: {{ $transaction->id }}</p>
    <p>Amount: {{ $transaction->value }}</p>
    <p>Transaction Date: {{ $transaction->created_at }}</p>
    <p>Transaction Type: {{ $transaction->type->name() }}</p>
    <p>Payer: {{ $transaction->payer->name }}</p>
    <p>Payee: {{ $transaction->payee->name }}</p>
</div>
