<html>
<head>
  <title>Thank you</title>
  <link rel="stylesheet" type="text/css" href="css/background.css">
  <style>
  body{
    position: fixed;
     top: 30%;
  }
  </style>
  <script type="text/javascript">
  function hideblock()
{
  window.parent.frames['Left_menu'].document.getElementById("checkoutblockbg").style.display = "none";
  window.parent.frames['Left_menu'].document.getElementById("checkoutblock").style.display = "none";
}
  </script>
</head>
<body>
  <?php
  session_start();
  $count = 0;

  $headers = 'MIME-version: 1.0' . "\r\n";
  $headers .= 'Content-type : text/html; charset=iso-8859-1' . "\r\n";
  $headers .= 'From: Online Grocery Store<PattrickMatthew.Hartono-1@student.uts.edu.au>' ."\r\n";

  $to = $_POST['email'];
  $sub = "Order Details";

  $msg = "<html>
  <head>
  <title>Order details</title>
  </head>
  <body>
  <p>
  Your contact details are:
  </p>
  <table cellspacing=\"4\" cellpadding=\"4\" border=\"1\">

  <tr>
  <td> Name </td>
  <td>". $_POST['firstname'] ." " .$_POST['lastname']. "</td>
  </tr>

  <tr>
  <td> Address </td>
  <td>". $_POST['address'] ." </td>
  </tr>

  <tr>
  <td> Suburb </td>
  <td>". $_POST['suburb'] ." </td>
  </tr>

  <tr>
  <td> State </td>
  <td>". $_POST['state'] ." </td>
  </tr>

  <tr>
  <td> Country </td>
  <td>". $_POST['country'] ." </td>
  </tr>

  <tr>
  <td> Post Code </td>
  <td>". $_POST['postcode'] ." </td>
  </tr>

  <tr>
  <td> Email </td>
  <td>". $_POST['email'] ." </td>
  </tr>
  </table>

  <p>
  Your order details are:
  </p>

  <table cellspacing=\"4\" cellpadding=\"4\" border=\"1\">
  <tr>
  <td>Product Name</td>
  <td>Unit Price</td>
  <td>Unit Quantity</td>
  <td>Required Quantity</td>
  <td>Total</td>
  </tr>";

  if(!empty($_SESSION['g']))
  {
    while ($count < $_SESSION['g']) {
      if (empty($_SESSION['product_name'][$count]))
      {
        $count++;
        continue;
      }
      else {
        $msg .="<tr>
        <td>" . $_SESSION['product_name'][$count] ."</td>
        <td>" . $_SESSION['unit_price'][$count] ."</td>
        <td>" . $_SESSION['unit_quantity'][$count] ."</td>
        <td>" . $_SESSION['quantity'][$count] ."</td>
        <td>" . $_SESSION['total'][$count] ."</td>
        </tr>";
        $count = $count+1;
      }
    }
  }

  $msg .= "
  <tr>
  <td>Number of products</td>
  <td>" . $_SESSION['count'] ." </td>
  </tr>
  <tr>
  <td>Shopping cart total</td>
  <td>" . $_SESSION['cart_total'] ." $</td>
  </tr>
  </table>
  </body>
  </html>";

  if(mail($to,$sub,$msg,$headers))
   {
     echo("<center><p><b>Thanks for your purchasing from our store!</b><br> The detail of your order will be emailed shortly.</p></center>");
     session_destroy();
     hideblock();
   }
 else{
     echo("<p>Email is not sent. Please try again.</p>");
   }
  ?>


</body>
</html>
