{{$order->firstname}}
{{$order->lastname}}
You made an order recently on our website 

this is your total price details 
<tr>
<td colspan="3"></td>
<td style="font-size:15px; font-weight:bold;">Tax: N{{$order->tax}}</td>
</tr>

<tr>
<td colspan="3"></td>
<td style="font-size:15px; font-weight:bold;">Shipping:Free shipping </td>
</tr>

<tr>
<td colspan="3"></td>
<td style="font-size:22px; font-weight:bold;">Total: N{{$order->subtotal}}</td>