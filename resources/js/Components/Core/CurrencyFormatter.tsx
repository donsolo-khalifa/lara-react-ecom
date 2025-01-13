function CurrencyFormatter({
  amount,
  currency = "NGN",
  local,
}: {
  amount: number,
  currency?: string,
  local?: string
}) {
  return new Intl.NumberFormat(local,{
    style: 'currency',
    currency
  }).format(amount);
}

export default CurrencyFormatter;
