<!DOCTYPE html>
<html lang="en">
<head>
  <title>Bootstrap Example</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</head>
<body>
  <header>
       <?php    
            require('blocks\header.php');
       ?>
    </header>


<div class="container mt-3">
  <div class="col-9">
    <h2 class="text-center mb-3">Принять товар</h2>


    <form id = "addPurchaseOrderForm"  onsubmit="return submitForm()">
      <div class="container">
        <div class="form-group row">
          <div class="col-12">
            <select class="custom-select mb-3" id="supplier-select" name="supplier-select" required>
                <option selected disabled value=''>Выберите поставщика</option>
                
                <?php 
                                                require('mysql_connect.php');
                                                $sql = 'SELECT SupplierID,Name FROM Suppliers ORDER BY SupplierID';
                                                $query = $pdo->query($sql);

                                                while ($row = $query->fetch(PDO::FETCH_OBJ))
                                                {
                                                ?>
                      <option value="<?= $row->SupplierID ?>"><?= $row->Name ?></option>
                  <?php };?>

              </select>
            </div>
          </div>

      
        <div class="row d-flex justify-content-center">
  <div class="col-9"> 
                        <?php 
                                                $sql = 'SELECT * FROM SupplierGoods ORDER BY SupplierGoodID';
                                                $query = $pdo->query($sql);

                                                while ($row = $query->fetch(PDO::FETCH_OBJ))
                                                {
                                        
                                        ?> 
      <div class="row d-flex justify-content-end">      
            <div class=" flex-shrink-1">
          <label for="supplier-goods<?= $row->SupplierGoodID ?>" class="input-group-text bg-transparent border-0"><?= $row->Name ?></label>
            </div>           
                <div class="input-group form-group col-7">                                      
                    <input type="number" class="form-control rounded supplier-goods" id="supplier-goods<?= $row->SupplierGoodID ?>" data-index-number="<?= $row->SupplierGoodID ?>" min="0" max="2147483647" onkeydown="return (event.keyCode !== 69 && event.keyCode !== 190 && event.keyCode !== 107 && event.keyCode !== 109)" >
                </div>
      </div>


      <?php };?>

             </div> 
         </div>
        

       
    </div>      

    <div class="row">
        <button type="submit" class="btn btn-primary btn-block mt-3" id="btnAddPurchaseOrder" >Принять</button>
    </div>

    
    <div class="row">
      <button type="button" class="btn btn-light btn-block mt-2" id="btnClear" onclick="resetForm()">Очистить</button>
    </div>                                                   
  </div>
    </form>
</div>



<script>
  let btn = document.getElementById('btnAddPurchaseOrder');


  function addPurchaseOrder()  {
    
   
    let orderList = document.getElementsByClassName('supplier-goods');
    let sp = document.getElementById("supplier-select").value;
    
    let f = document.getElementById("addPurchaseOrderForm");    
    if (!f.checkValidity()) {
      return;
      }


    
      let jsonObj = [];
      for (let i = 0; i < orderList.length; i++) {

        if (Number(orderList[i].value)>0)
          {
            jsonObj.push({ 
                  'SupplierID':sp,  
                  'SupplierGoodID': orderList[i].dataset.indexNumber,
                  'NumberOfUnits': orderList[i].value
              });
          }
      }  


     $.ajax({
              url: 'addPurchaseOrder.php',
              type: 'POST',
              cache: false,
              data: {'title' : JSON.stringify(jsonObj)}, 
              dataType: 'html',
              success: function(data) {
                if (data == 'Готово') {
                        alert('Готово');
                }
                else {  
                        alert('Возникла ошибка');

                }
              }        
          }); 
       // }
};



let orderList =  document.getElementsByClassName('supplier-goods');
console.log(orderList)

    Array.prototype.forEach.call(orderList, function(item) {
          item.addEventListener("change", function() {

                let labelElements = item.labels;
                if (Number(item.value)>0)
                  {
                    labelElements[0].classList.add("text-success");;
                  }
                  else {
                    labelElements[0].classList.remove("text-success");;
                  }          
                console.log(item);         
            });
      });


 
 
function submitForm() {

              let orderList =  document.getElementsByClassName('supplier-goods');
              let i = 0;  

              Array.prototype.forEach.call(orderList, function(item) {

                        if (Number(item.value)>0) {
                          i=1;
                          addPurchaseOrder();
                        }          
                });
                if (i == 0)
                            {
                              alert("Не выбран вид товара");
                              return false;
                            }
                            
};



function resetForm() {
  document.getElementById("addPurchaseOrderForm").reset();

  Array.prototype.forEach.call(orderList, function(item) {

                let labelElements = item.labels;
                    labelElements[0].classList.remove("text-success");;              
      });
}

</script>

</body>
</html>