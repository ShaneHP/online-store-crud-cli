<?php

    //Works on windows using command line interface
    //Connecting to a remotemysql database
    /*Database Design*/
    /*My database contains 3 tables, 1 containing customers personal details, 1 containing addresses, and finally 1 table that 
    links these 2 tables together using foreing keys. The linking table uses on delete cascade so that when a record is deleted from
    either the customers or addresses table, the record linking the 2 is also deleted*/

    //info for online database
    $servername = "remotemysql.com";
    $username = "uqatNi9icE";
    $password = "TIJ7EiKxBA";
    $dbname = "uqatNi9icE";

    //connect to database
    $conn = mysqli_connect($servername, $username, $password, $dbname);

    //check connection
    if(!$conn){
      echo "Connection error: " . mysqli_connect_error();
    }

    /*CREATE FUNCTIONALITY*/
    
    //this creates the customer personal info record
    $sql = "INSERT INTO customers (customer_id, title, first_name, surname, mobile, email) VALUES (NULL, 'Mr', 'Shane', 'Hyland', '0898765432', 'shane@gmail.com')";
    if(mysqli_query($conn, $sql)){          //checks if insert statement was successful
        $last_cust_id = mysqli_insert_id($conn);        //records customer id of the customer just created to use when associating this customer with their address
        echo "New customer added, Customer ID is: " . $last_cust_id . "\r\n"; 
    }else{
        echo "Error: " . $sql . "\r\n" . mysqli_error($conn);
    }

    //this creates the customer address info record
    $sql = "INSERT INTO addresses (address_id, address_line1, address_line2, town, county, eircode) VALUES (NULL, '532 Riverforest', 'Confey', 'Leixlip', 'Kildare', 'A32 X5R5')";
    if(mysqli_query($conn, $sql)){
        $last_adr_id = mysqli_insert_id($conn);     //records last address id
        echo "New address added, Address ID is: " . $last_adr_id . "\r\n";
    }else{
        echo "Error: " . $sql . "\r\n" . mysqli_error($conn);
    }

    //this inserts the last customer id and last address id into the table that keeps track of the associations
    $sql = "INSERT INTO customeraddress (customer_id, address_id, home_address, shipping_address) VALUES ($last_cust_id, $last_adr_id, 'yes', 'yes')";
    if(mysqli_query($conn, $sql)){
        echo "Customer ID: " . $last_cust_id . " associated with Address ID: " . $last_adr_id . "\r\n" . "\r\n";
    }else{
        echo "Error: " . $sql . "\r\n" . mysqli_error($conn);
    }
    
    /*RETRIEVE FUNCTIONALITY*/
    
    //join tables together in a select query and select the data of someone called Bill
    $sql = 'SELECT * FROM customers, addresses, customeraddress WHERE (customers.customer_id=customeraddress.customer_id AND addresses.address_id=customeraddress.address_id) AND customers.first_name = "Bill"';
    if(mysqli_query($conn, $sql)){
        $result = mysqli_query($conn, $sql);    //put result into a variable
        echo "Query Successful" . "\r\n" . "\r\n";
    }else{
        echo "Error: " . $sql . "\r\n" . mysqli_error($conn);
    }

   //convert result to an array
    $users = mysqli_fetch_all($result, MYSQLI_ASSOC);

    //free result from memory
    mysqli_free_result($result);   

    //log result to console
    foreach($users as $user){
        echo "Title: " . $user['title'] . "\r\n" . "First Name: " . $user['first_name'] . "\r\n" . "Surname: " . $user['surname'] . "\r\n" . "Email: " . $user['email'] . "\r\n" . "Mobile: " . $user['mobile'] . "\r\n"
        . "Address Line 1: " . $user['address_line1'] . "\r\n" . "Address Line 2: " . $user['address_line2'] . "\r\n" . "Town: " . $user['town'] . "\r\n" . "County: " . $user['county'] . "\r\n"
        . "Eircode: " . $user['eircode'] . "\r\n" . "Home Address?: " . $user['home_address'] . "\r\n" . "Shipping Address?: " . $user['shipping_address'] . "\r\n" . "\r\n";
    }

    /*UPDATE FUNCTIONALITY*/
    
    //update the title, email, mobile, and address line 1 of a customer called Jenny
    $sql = 'UPDATE customers, addresses, customeraddress SET customers.mobile = "0897652341", customers.title = "Dr", customers.email = "jennywhite300@outlook.com", addresses.address_line1 = "Flat 6" 
    WHERE (first_name = "Jenny") AND (customers.customer_id=customeraddress.customer_id AND addresses.address_id=customeraddress.address_id)';
    if(mysqli_query($conn, $sql)){
        echo "Update Successful" . "\r\n" . "\r\n";
    }else{
        echo "Error: " . $sql . "\r\n" . mysqli_error($conn);
    }
    
    /*DELETE FUNCTIONALITY*/

    //Delete the user information and their address if they match the name, mobile and email
    $sql = 'DELETE customers, addresses FROM customers, addresses, customeraddress 
    WHERE (customers.mobile = "0869874123" AND customers.email = "john.boy@gmail.com" AND customers.first_name = "John") 
    AND (customers.customer_id=customeraddress.customer_id AND addresses.address_id=customeraddress.address_id)';
    if(mysqli_query($conn, $sql)){
        echo "Record Deleted" . "\r\n" . "\r\n";
    }else{
        echo "Error: " . $sql . "\r\n" . mysqli_error($conn);
    }
   
    //close connection to database
    mysqli_close($conn);
    
?>
