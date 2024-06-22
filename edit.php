<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Item</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #2c3e50;
            color: #ecf0f1;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }
        .container {
            background-color: #34495e;
            padding: 40px;
            border-radius: 10px;
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.3);
            max-width: 400px;
            width: 100%;
            text-align: center;
        }
        h2 {
            margin-bottom: 20px;
        }
        .form-group {
            margin-bottom: 20px;
            text-align: left;
        }
        .form-group label {
            display: block;
            margin-bottom: 5px;
        }
        .form-group input[type="text"],
        .form-group select {
            width: 100%;
            padding: 10px;
            border: none;
            border-radius: 5px;
            box-sizing: border-box;
            font-size: 16px;
            color: #2c3e50;
        }
        .form-group button {
            width: 100%;
            padding: 10px;
            border: none;
            border-radius: 5px;
            background-color: #e74c3c;
            color: #ecf0f1;
            font-size: 16px;
            cursor: pointer;
            transition: background-color 0.3s;
        }
        .form-group button:hover {
            background-color: #c0392b;
        }
        .form-links a {
            display: inline-block;
            margin-top: 10px;
            color: #FFFFFF;
            text-decoration: none;
            transition: color 0.3s;
        }
        .form-links a:hover {
            color: #2980b9;
        }
        .error {
            color: #dc3545;
            margin-bottom: 15px;
        }
        .success {
            color: #28a745;
            margin-bottom: 15px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Edit Item</h2>
        <?php
            $id = "";
            $var_description = "";
            $var_qty = "";
            $var_price = "";
            $var_status = "";

            $errors = ""; //variable to hold all errors

            if(isset($_GET["record"]))
            {
                $id = $_GET["record"]; //get the value of the record from the URL parameter
                
                //query the record again, only 1 record
                $db = mysqli_connect("localhost", "root", "", "sales");
                $sql = "select * from item where id = $id";                
                $record = mysqli_query($db, $sql);
                
                //if the record exists, assign all its values to PHP variables...
                if(mysqli_num_rows($record) > 0)
                {
                    //get the 1 record only
                    $rec = mysqli_fetch_array($record);
                    
                    //assign to PHP variables
                    $var_description = $rec["description"];
                    $var_qty = $rec["qty"];
                    $var_price = $rec["price"];
                    $var_status = $rec["status"];                                        
                }
                else 
                {
                    echo "<p class='message error'>Record is no longer existing...</p>";
                }
            }

            if(isset($_POST["BtnSaveChangesItem"]))
            {
                //get all inputs including the ID
                $id = $_POST["TxtID"];
                $var_description = trim($_POST["TxtDescription"]);
                $var_qty = trim($_POST["TxtQty"]);
                $var_price = trim($_POST["TxtPrice"]);
                $var_status = $_POST["CboStatus"];                
            
                
                //error trappings
                if(! is_numeric($var_qty))
                {
                    $errors = $errors . "<p class='message error'>Quantity must be a number.</p>";
                    $var_qty = "";
                }
                
                if(! is_numeric($var_price))
                {
                    $errors = $errors . "<p class='message error'>Price must be a number.</p>";
                    $var_price = "";
                }
                    
                if(intval($var_qty) < 0 || intval($var_qty) > 9999)
                {
                    $errors = $errors . "<p class='message error'>Quantity value must be between 1 and 9,999.</p>";
                    $var_qty = "";
                }
                    
                if(doubleval($var_price) < 0 || doubleval($var_price) > 999999)    
                {
                    $errors = $errors . "<p class='message error'>Price value must be between 1.00 and 999,999.00.</p>";
                    $var_price = "";
                }
                    
                if(is_numeric($var_qty) && intval($var_qty) == 0 && $var_status != "S")
                    $errors = $errors . "<p class='message error'>Quantity entered is 0. Status must be SOLD-OUT.</p>";
                    
                if(is_numeric($var_qty) && intval($var_qty) > 0 && $var_status != "A")
                    $errors = $errors . "<p class='message error'>Quantity entered is greater than 0. Status must be AVAILABLE.</p>";                
                            
                
                //check if there are errors, do not proceed if there are errors found
                if($errors != "") //if errors not equal to empty, meaning naay errors nakita
                {
                    echo $errors;
                } 
                else 
                {
                    //if there are no errors, proceed
                    //open a connection
                    $db = mysqli_connect("localhost", "root", "", "sales");
                    if($db)
                    {
                        //create the update sql statement
                        $sql = " update 
                                    item
                                 set
                                    description = '".$var_description."', 
                                    qty = ".$var_qty.", 
                                    price = ".$var_price.",
                                    status = '".$var_status."'
                                where 
                                    id = $id ";
                                
                        //execute the update sql statement
                        $query = mysqli_query($db, $sql);
                        if($query)
                        {
                            echo "<p class='message success'><b>Record was updated successfully...</b></p>";
                        }
                        else 
                        {
                            echo "<p class='message error'>Something went wrong in your query...</p>";
                        }
                    }
                    else 
                    {
                        echo "<p class='message error'>Error connecting to database sales...</p>";
                    }
                    
                    //always close the connection
                    mysqli_close($db);                    
                }
            }
        ?>
        <form method="POST" action="edit.php">
            <div class="form-group">
                <label for="TxtID">ID:</label>
                <input type="text" id="TxtID" name="TxtID" readonly value="<?php echo $id; ?>">
            </div>
            <div class="form-group">
                <label for="TxtDescription">Description:</label>
                <input type="text" id="TxtDescription" name="TxtDescription" maxlength="100" required value="<?php echo $var_description; ?>">
            </div>
            <div class="form-group">
                <label for="TxtQty">Quantity:</label>
                <input type="text" id="TxtQty" name="TxtQty" required value="<?php echo $var_qty; ?>">
            </div>
            <div class="form-group">
                <label for="TxtPrice">Price:</label>
                <input type="text" id="TxtPrice" name="TxtPrice" required value="<?php echo $var_price; ?>">
            </div>
            <div class="form-group">
                <label for="CboStatus">Status:</label>
                <select id="CboStatus" name="CboStatus">
                    <option value="A" <?php if($var_status == "A") echo "selected"; ?>>Available</option>
                    <option value="S" <?php if($var_status == "S") echo "selected"; ?>>Sold Out</option>
                </select>
            </div>
            <div class="form-group">
                <button type="submit" name="BtnSaveChangesItem">Save Changes</button>
            </div>
            <div class="form-links">
                <a href="edit.php">Clear Inputs</a> <br>
                <a href="list_all.php">Back to List</a>
            </div>
        </form>
    </div>
</body>
</html>
