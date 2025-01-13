<?php
    //require_once '';
    include "dbconfig.php";
if (isset($_POST["import"])) {
    
    $fileName = $_FILES["file"]["tmp_name"];
    
    if ($_FILES["file"]["size"] > 0) {
        
        $file = fopen($fileName, "r");
        $find_header=0;
        while (($column = fgetcsv($file, 200, ";")) !== FALSE) {
            $find_header++; //update counter
            //this ensures we skip the header
            if( $find_header > 1 ) {
                //the column variable corresponds with the ones in your csv file
            $sqlInsert = "INSERT into `x92374_pages` (page_alias,page_state,page_cat,page_title,page_desc,page_keywords,page_metatitle,page_metadesc,page_text,page_parser,page_author,page_ownerid,page_date,page_begin,page_expire,page_updated,page_file,page_url,page_size,page_count,page_filecount)
                   values ('" . $column[0] . "',
				   '" . $column[1] . "',
				   '" . $column[2] . "',
				   '" . $column[3] . "',
				   '" . $column[4] . "',
				   '" . $column[5] . "',
				   '" . $column[6] . "',
				   '" . $column[7] . "',
				   '" . $column[8] . "',
				   '" . $column[9] . "',
				   '" . $column[10] . "',
				   '" . $column[11] . "',
				   '" . $column[12] . "',
				   '" . $column[13] . "',
				   '" . $column[14] . "',
				   '" . $column[15] . "',
				   '" . $column[16] . "',
				   '" . $column[17] . "',
				   '" . $column[18] . "',
				   '" . $column[19] . "',
				   '" . $column[20] . "',
				   '" . $column[21] . "')";
            $result = mysqli_query($db, $sqlInsert);
            
            if (!empty($result)) {
                $type = "success";
                $message = "CSV Data Imported into the Database";
            } else {
                $type = "error";
                $message = "Problem in Importing CSV Data";
            }
        }
        }
    }
}
?>
<!DOCTYPE html>
<html>
<head>
<title>Welcome Home</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.0/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
</head>
<body>
<div class="container">  
    <div class="jumbotron">
    </div>   

<form class="form-horizontal" action="" method="post" name="uploadCSV"
    enctype="multipart/form-data">
    <div class="input-row">
        <label class="col-md-4 control-label">Choose CSV File</label> <input
            type="file" name="file" id="file" accept=".csv">
        <button type="submit" id="submit" name="import"
            class="btn-submit">Import</button>
        <br />

    </div>
    <div id="labelError"></div>
</form>
</div>
<hr>
<br>
<?php
$sqlSelect = "SELECT * FROM x92374_pages";
$result = mysqli_query($db, $sqlSelect);
            
if (mysqli_num_rows($result) > 0) {
?>
<?php echo $message; ?>
<table id='userTable'>
    <thead>
        <tr>,,,,,
            <th><b>[0]</b> page_id</th>
            <th><b>[1]</b> page_alias</th>
            <th><b>[2]</b> page_state</th>
            <th><b>[3]</b> page_cat</th>
            <th><b>[4]</b> page_title</th>
            <th><b>[5]</b> page_desc</th>
            <th><b>[6]</b> page_keywords</th>
            <th><b>[7]</b> page_metatitle</th>
            <th><b>[8]</b> page_metadesc</th>
            <th><b>[9]</b> page_text</th>
            <th><b>[10]</b> page_parser</th>
            <th><b>[11]</b> page_author</th>
            <th><b>[12]</b> page_ownerid</th>
            <th><b>[13]</b> page_date</th>
            <th><b>[14]</b> page_begin</th>
            <th><b>[15]</b> page_expire</th>
            <th><b>[16]</b> page_updated</th>
            <th><b>[17]</b> page_file</th>
            <th><b>[18]</b> page_url</th>
            <th><b>[19]</b> page_size</th>
            <th><b>[20]</b> page_count</th>
            <th><b>[21]</b> page_filecount</th>
        </tr>
    </thead>
    <?php
	while ($row = mysqli_fetch_array($result)) {
    ?>

    <tbody>
        <tr>
            <td><?php  echo $row['page_id']; ?></td>
            <td><?php  echo $row['page_alias']; ?></td>
            <td><?php  echo $row['page_state']; ?></td>
            <td><?php  echo $row['page_cat']; ?></td>
            <td><?php  echo $row['page_title']; ?></td>
            <td><?php  echo $row['page_desc']; ?></td>
            <td><?php  echo $row['page_keywords']; ?></td>
            <td><?php  echo $row['page_metatitle']; ?></td>
            <td><?php  echo $row['page_metadesc']; ?></td>
            <td><?php  echo $row['page_text']; ?></td>
            <td><?php  echo $row['page_parser']; ?></td>
            <td><?php  echo $row['page_author']; ?></td>
            <td><?php  echo $row['page_ownerid']; ?></td>
            <td><?php  echo $row['page_date']; ?></td>
            <td><?php  echo $row['page_begin']; ?></td>
            <td><?php  echo $row['page_expire']; ?></td>
            <td><?php  echo $row['page_updated']; ?></td>
            <td><?php  echo $row['page_file']; ?></td>
            <td><?php  echo $row['page_url']; ?></td>
            <td><?php  echo $row['page_size']; ?></td>
            <td><?php  echo $row['page_count']; ?></td>
            <td><?php  echo $row['page_filecount']; ?></td>
        </tr>
     <?php
     }
     ?>
    </tbody>
</table>
<?php } ?>
<script type="text/javascript">
	$(document).ready(
	function() {
		$("#frmCSVImport").on(
		"submit",
		function() {

			$("#response").attr("class", "");
			$("#response").html("");
			var fileType = ".csv";
			var regex = new RegExp("([a-zA-Z0-9\s_\\.\-:])+("
					+ fileType + ")$");
			if (!regex.test($("#file").val().toLowerCase())) {
				$("#response").addClass("error");
				$("#response").addClass("display-block");
				$("#response").html(
						"Invalid File. Upload : <b>" + fileType
								+ "</b> Files.");
				return false;
			}
			return true;
		});
	});
</script>
</body> 
</html>