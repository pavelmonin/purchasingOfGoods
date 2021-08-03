<!DOCTYPE html>
<html lang="en">
<head>
  <title>Bootstrap Example</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
   <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script> 
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">

  <script type="text/javascript" src="https://cdn.jsdelivr.net/jquery/latest/jquery.min.js"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
</head>
<body>
    <header>
        <?php    
                require('blocks\header.php');

                require('mysql_connect.php');
                                                
                $sql ="SELECT DATE_FORMAT(MIN(OrderDate),'%m/%d/%Y') as minOrderDate,DATE_FORMAT(Now(),'%m/%d/%Y') as maxOrderDate FROM PurchaseOrders";

                $query = $pdo->query($sql);
                $dt = $query->fetch(PDO::FETCH_OBJ);

        ?>
    </header>


<div class="container mt-3">

  <div class="col-9">




<div class="container">
  <h2 class="text-center mb-3">Поступление видов продукции</h2>
  <div class="input-group form-group row-cols-3">
    <div class="d-flex justify-content-end col-2">

      <label for="floatingInputDR" class="input-group-text bg-transparent border-0 mb-auto">Период</label>
    </div>
  <input type="text" class="custom-select rounded text-center mb-3 form-control col-8" name="daterange" value="<?= $dt->minOrderDate ?> - <?= $dt->maxOrderDate ?>"  id="purchasePeriod" readonly />          
</div>

  
  <table class="table table-bordered">
    <thead>


      <tr>
        <th>Вид продукции</th>
        <th>Поставщик</th>
        <th>Итоговый вес</th>
        <th>Итоговая стоимость</th>
      </tr>
    </thead>

    <tbody id="summarizedPurchasesTable">

    </tbody>
  </table>
</div>


</div>  
</div>


<script>
        $(function() {
          $('input[name="daterange"]').daterangepicker({
            "locale": {
                    "format": "MM/DD/YYYY",
                    "separator": " - ",
                    "applyLabel": "Ок",
                    "cancelLabel": "Отмена",
                    "fromLabel": "От",
                    "toLabel": "До",
                    "customRangeLabel": "Произвольный",
                    "weekLabel": "W",
                    "daysOfWeek": [
                                  "Вс",
                                  "Пн",
                                  "Вт",
                                  "Ср",
                                  "Чт",
                                  "Пт",
                                  "Сб"
                              ],
                    "monthNames": [
                        "Январь",
                        "Февраль",
                        "Март",
                        "Апрель",
                        "Май",
                        "Июнь",
                        "Июль",
                        "Август",
                        "Сентябрь",
                        "Октябрь",
                        "Ноябрь",
                        "Декабрь"
                    ],
                    "firstDay": 1
                  },
            
            opens: 'center',
            minDate:"<?= $dt->minOrderDate ?>",
            maxDate: "<?= $dt->maxOrderDate ?>",
            showDropdowns: true
            
          
          }, function(start, end, label) {
            console.log("A new date selection was made: " + start.format('YYYY-MM-DD') + ' to ' + end.format('YYYY-MM-DD'));
          });
        });




    let purchasePeriod = document.getElementById("purchasePeriod");

    purchasePeriod.onchange = getSummarizedPurchases;
    
    function getSummarizedPurchases (){ 
            
            let [start,end] = purchasePeriod.value.trim().split("- ");
            start = start.slice(6,10)+'-'+start.slice(0,2)+'-'+start.slice(3,5);
            end = end.slice(6,10)+'-'+end.slice(0,2)+'-'+end.slice(3,5);

            // start='2021-07-01';end= '2051-07-01';
            const dbParam = JSON.stringify({start,end});
            const xmlhttp = new XMLHttpRequest();
            xmlhttp.onload = function() {
                  const myObj = JSON.parse(this.responseText);
                  let text ="";
                  let table = document.getElementById("summarizedPurchasesTable");
                  table.innerHTML = "";
                  for (let x in myObj) {
                    
                    var row = table.insertRow(-1);
                    var cell1 = row.insertCell(0);
                    var cell2 = row.insertCell(1);
                    var cell3 = row.insertCell(2);
                    var cell4 = row.insertCell(3);
                    cell1.innerHTML = myObj[x].SupplierGood;
                    cell2.innerHTML = myObj[x].Supplier;
                    cell3.innerHTML = myObj[x].NumberOfUnitsTotal;
                    cell4.innerHTML = myObj[x].PriceTotal;
                  }
                }
          xmlhttp.open("POST", "getSummarizedPurchases.php");
          xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
          xmlhttp.send("x=" + dbParam);

    }
 

    window.onload = getSummarizedPurchases;   
  </script>
</body>
</html>