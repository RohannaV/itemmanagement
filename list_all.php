<html>
<head>
    <title>List All Items</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #2c3e50;
            margin: 0;
            padding: 0;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
        }

        .container {
            background-color: #fff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            width: 80%;
            max-width: 800px;
        }

        h2, h3 {
            text-align: center;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th, td {
            padding: 10px;
            border: 1px solid #ddd;
            text-align: left;
        }

        th {
            background-color: #f2f2f2;
        }

        tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        a {
            color: #007bff;
            text-decoration: none;
        }

        a:hover {
            text-decoration: underline;
        }

        .message {
            color: #fff;
            padding: 10px;
            margin: 10px 0;
            border-radius: 5px;
            text-align: center;
        }

        .error {
            background-color: #dc3545;
        }

        .success {
            background-color: #28a745;
        }

        .btn-back {
            display: inline-block;
            margin-top: 20px;
            padding: 10px 20px;
            color: #fff;
            background-color: #007bff;
            border: none;
            border-radius: 5px;
            text-decoration: none;
            text-align: center;
        }

        .btn-back:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>List of All Items</h2>
        <?php
            $db = mysqli_connect("localhost", "root", "", "sales");
            if($db) //if connection is ok
            {
                //check if there is a record url parameter variable
                if(isset($_GET["record"]))
                {
                    $id = $_GET["record"]; //get the value of the record from the URL parameter
                    
                    //query the record again, only 1 record, check if the record is still existing...
                    //cannot delete a record that is no longer existing...					
                    $sql = "select * from item where id = $id";				
                    $record = mysqli_query($db, $sql);
                    
                    //if the record exists, proceed on deleting that record...
                    if(mysqli_num_rows($record) > 0)
                    {
                        //create the delete sql statement
                        $sql = "delete from item where id = $id ";
                        
                        //execute the delete sql statement
                        $query = mysqli_query($db, $sql);
                        if($query)
                        {
                            echo "<p class='message success'><b>Record was deleted successfully...</b></p>";
                        }
                        else 
                        {
                            echo "<p class='message error'>Something went wrong in your query...</p>";
                        }						
                    }
                    else 
                    {
                        echo "<p class='message error'>Record is no longer existing...</p>";
                    }
                }				
				
                //query all the record items
                //create the select sql statement
                $sql = "select * from item order by description";
                
                //execute the query and all records will be assigned to $records variable from the database
                $records = mysqli_query($db, $sql);
                
                //check if there are records retrieved
                if(mysqli_num_rows($records) > 0) 
                {
                    //if there are records
                    //display the list in a html table format
                    echo "<table>";
                    echo "    <thead>";
                    echo "        <tr>";
                    echo "            <th>Seq#</th>";
                    echo "            <th>ID</th>";
                    echo "            <th>Description</th>";
                    echo "            <th>Quantity</th>";
                    echo "            <th>Price</th>";
                    echo "            <th>Status</th>";
                    echo "            <th></th>";
                    echo "            <th></th>";
                    echo "        </tr>";
                    echo "    </thead>";
                    echo "    <tbody>";
                    
                    //loop each record here from $records variable
                    $sequence = 1;
                    while($rec = mysqli_fetch_array($records))
                    {
                        //use the column names from items table in the array as subscript or index
                        echo "<tr>";
                        echo "    <td>$sequence.</td>";
                        echo "    <td>".$rec["id"]."</td>";
                        echo "    <td>".$rec["description"]."</td>";
                        echo "    <td align='right'>".$rec["qty"]."</td>";
                        echo "    <td align='right'>".$rec["price"]."</td>";
                        echo "    <td>".$rec["status"]."</td>";
                        echo "    <td><a href='edit.php?record=".$rec["id"]."'>Edit</a></td>";
                        echo "    <td><a href='list_all.php?record=".$rec["id"]."' onclick='return confirm(\"Are you sure you want to delete this record?\");'>Delete</a></td>";
                        echo "</tr>";
                        
                        //add 1 to sequence variable after each record
                        $sequence = $sequence + 1;
                    }
                    
                    echo "    </tbody>";
                    echo "</table>";
                    
                }
                else 
                {
                    //if no records found, display error message
                    echo "<p class='message error'>No records found...</p>";
                }
                mysqli_close($db);
            }
            else 
            {
                echo "<p class='message error'>Error connecting to database sales...</p>";
            }
        ?>
        <h3><a class="btn-back" href="add.php">Add More Items</a></h3>
    </div>
</body>
</html>
