<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
<link rel="stylesheet" type="text/css" href="css/background.css">
<link rel="stylesheet" type="text/css" href="css/input.css">
    <meta charset="utf-8">
    <title></title>
    <script language="javascript">
    function checkout(ToCheckIt)
    {
        if (ToCheckIt == 0 || !(ToCheckIt))
        {
            window.alert("Your Shopping cart is empty, please select something!");
            return false;
        }
        else
        {
            return true;
        }
    }
    function showbg()
{
	window.parent.frames['Left_menu'].document.getElementById("checkoutblockbg").style.display = "block";
	window.parent.frames['Left_menu'].document.getElementById("checkoutblock").style.display = "block";
}
    </script>

  </head>
  <body>
    <center><h2>Shopping Cart</h2></center>
    <br>
    <form action = "<?=$_SERVER['PHP_SELF'];?>" method="post" target="bottom_right">
    <?php
    session_start();

    if(isset($_POST['clear']))
    {
      unset($_SESSION['product_name']);
      unset($_SESSION['unit_price']);
      unset($_SESSION['unit_quantity']);
      unset($_SESSION['quantity']);
      unset($_SESSION['total']);
      unset($_SESSION['g']);
      unset($_SESSION['cart_total']);
      unset($_SESSION['count']);
    }
    if (!empty($_POST['delete']))
    {
      foreach($_POST['delete'] as $val)
      {
        unset($_SESSION['product_name'][$val]);
        unset($_SESSION['unit_price'][$val]);
        unset($_SESSION['unit_quantity'][$val]);
        unset($_SESSION['quantity'][$val]);
        unset($_SESSION['total'][$val]);
        unset($_SESSION['cart_total']);
        $_SESSION['count']--;
      }
    }

    if (empty($_SESSION['g']))
    {
      $_SESSION['g'] = 0;
    }
    $cart_total = 0;
    $total = 0;
    $count = 0;
    $count1 =0;

    //print table here
    print "<table width=90% border=1>";
    print "<tr>";
    print "<td>Select</td>";
    print "<td>Product Name</td>";
    print "<td>Price</td>";
    print "<td>Unit Quantity</td>";
    print "<td>Required Quantity</td>";
    print "<td>Total Cost</td>";
    print "</tr>";

    if(empty($_POST['delete']))
    {
      $link = mysql_connect("rerun.it.uts.edu.au","potiro","pcXZb(kL");

      if(!$link)
      {
        die("couldn't connect to mysql");
      }
      mysql_select_db("poti", $link) or die ("Couldn't connect to mysqleee");
      $product_id = $_SESSION['product_id'];
      $query = "select * from products where product_id=$product_id";
      $result = mysql_query($query);
      // $link = mysqli_connect("rerun","potiro","pcXZb(kL","poti");
      // if (!$link)
      //    die("Could not connect to Server");
      // $product_id = $_SESSION['product_id'];
      // $query_string = "select * from products where product_id = $product_id";
      // $result = mysqli_query($link,$query_string);

    }

    $judge = 0;
    if($result && $_POST['quantity'])
    {
      while ($row = mysql_fetch_array($result))
      {
        for ($i=0; $i < $_SESSION['g'] ; $i++) {
          if (($row["product_name"]==$_SESSION['product_name'][$i]) && ($row['unit_quantity']==$_SESSION['unit_quantity'][$i]))
          {
            if($_SESSION['quantity'][$i]+$_POST['quantity'] > $row['in_stock'])
            {
              $judge = 1;
              print "<script language=\"javascript\"> window.alert(\"you buy too much\"); </script>";
              break;
            }
            else {
              $_SESSION['quantity'][$i] += $_POST['quantity'];
              $_SESSION['total'][$i] = $_SESSION['unit_price'][$i]*$_SESSION['quantity'][$i];
              $judge = 1;
              break;
            }
          }
        }
        if ($judge != 1)
        {
          $_SESSION['product_name'][]=$row["product_name"];
          $_SESSION['unit_price'][]=$row["unit_price"];
          $_SESSION['unit_quantity'][]=$row["unit_quantity"];
          $_SESSION['quantity'][]=$_POST["quantity"];
          $total = $row['unit_price'] * $_POST['quantity'];
          $_SESSION['total'][]=$total;
          $_SESSION['g']=$_SESSION['g']+1;
        }
      }
    }

    if(!empty ($_SESSION['product_name']))
    {
      while ($count < $_SESSION['g'])
      {
        if(empty($_SESSION['product_name'][$count])){
          $count++;
          $count1++;
          continue;
        }
        else {
          print "<tr>";
          print "<td><input type=\"checkbox\" name=\"delete[]\" value=\"$count\"></td>\n";
          print "<td>".$_SESSION['product_name'][$count]."</td>";
          print "<td>".$_SESSION['unit_price'][$count]."</td>";
          print "<td>".$_SESSION['unit_quantity'][$count]."</td>";
          print "<td>".$_SESSION['quantity'][$count]."</td>";
          print "<td>$ ".$_SESSION['total'][$count]."</td>";
          print "</tr>";
          $cart_total=$cart_total + $_SESSION['total'][$count];
          $count = $count + 1;

        }
      }
          $_SESSION['cart_total'] = $cart_total;
          $_SESSION['count'] = $count - $count1;
          print "<tfoot><tr>";
          print '<td colspan="3">Number of products</td>';
          print '<td align ="center" colspan="3">'. $_SESSION['count'] . '</td></tr>';
          print '<tr><td colspan="3">Shopping cart total</td>';
          print '<td align ="center" colspan="3">'. $cart_total . '$</td>';
          print '</tr></tfoot></table>';

    }

        print "<table style=\"background-color: transparent; border-spacing:0; padding: 0; \" border=\"0\">";
        print "<tr><td>";
        print "<input type=\"submit\" name=\"clear\" value=\"Clear\" onclick=\"{if(confirm('Do you want to clear your shopping cart?')){return true;}return false;}\">";

        print "<td>";
        print "<input type=\"submit\" value=\"Delete\" onclick=\"{if(confirm('Do you want to delete the selected item>')){return true;}return false;}\">";
        print "</td>";

        print "</form>";
        print "<td>";
        print "<form action=\"checkout.php\" method=\"post\" target=\"top_right\">";
        print "<input type=\"submit\" name=\"submit\" value=\"Checkout\" onclick=\"showbg();return checkout(". $_SESSION['count'].");\">";
        print "</form>";
        print "</td></tr>";

      ?>

  </body>

</html>
