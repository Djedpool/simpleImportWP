<?php
global $wpdb;

$tablename = $wpdb->prefix."peoplestats";

if(isset($_POST['butimport'])){

  $extension = pathinfo($_FILES['import_file']['name'], PATHINFO_EXTENSION);
  $fullName = $_POST['full_name'] ?? null;
  $email = $_POST['email'] ?? null;
  $age = $_POST['age'] ?? null;

  if(!empty($_FILES['import_file']['name']) && $extension == 'csv'){

    $totalInserted = 0;

    $csvFile = fopen($_FILES['import_file']['tmp_name'], 'r');

    $file = fgetcsv($csvFile);

    echo '<pre>' . var_export($csvFile, true) . '</pre>';
    exit();

    while(($csvData = fgetcsv($csvFile)) !== FALSE){



      $csvData = array_map("utf8_encode", $csvData);



      $dataLen = count($csvData);

      $workoutType = trim($csvData[1]);
      $distance = trim($csvData[22]);
      $exerciseTime = trim($csvData[34]);

      $cntSQL = "SELECT count(*) as count FROM {$tablename} where username='".$username."'";
      $record = $wpdb->get_results($cntSQL, OBJECT);

      if($record[0]->count==0){

        if(!empty($name) && !empty($username) && !empty($email) && !empty($age) ) {

          $wpdb->insert($tablename, array(
            'name' =>$fullName,
            'email' =>$email,
            'age' => $age
          ));

          if($wpdb->insert_id > 0){
            $totalInserted++;
          }
        }

      }

    }
    echo "<h3 style='color: green;'>Total record Inserted : ".$totalInserted."</h3>";


  }else{
    echo "<h3 style='color: red;'>Invalid Extension</h3>";
  }

}

?>
<h2>All Entries</h2>

<!-- Form -->
<form method='post' action='<?= $_SERVER['REQUEST_URI']; ?>' enctype='multipart/form-data'>
    <input type="input" name="full_name"  placeholder="Puno ime">
    <input type="input" name="age" placeholder="Godine">
    <input type="file" name="import_file" >
    <input type="submit" name="butimport" value="Import">
</form>

<!-- Record List -->
<table width='100%' border='1' style='border-collapse: collapse;'>
   <thead>
   <tr>
     <th>S.no</th>
     <th>Name</th>
     <th>Username</th>
     <th>Email</th>
     <th>Age</th>
   </tr>
   </thead>
   <tbody>
   <?php
   // Fetch records
   $entriesList = $wpdb->get_results("SELECT * FROM ".$tablename." order by id desc");
   if(count($entriesList) > 0){
     $count = 0;
     foreach($entriesList as $entry){
        $id = $entry->id;
        $name = $entry->name;
        $username = $entry->username;
        $email = $entry->email;
        $age = $entry->age;

        echo "<tr>
        <td>".++$count."</td>
        <td>".$name."</td>
        <td>".$username."</td>
        <td>".$email."</td>
        <td>".$age."</td>
        </tr>
        ";
     }
   }else{
     echo "<tr><td colspan='5'>No record found</td></tr>";
  }
  ?>
  </tbody>
</table>