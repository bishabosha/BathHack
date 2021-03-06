<!doctype html>
<head>
    <title>Uploading</title>
    <link type="text/css" rel="stylesheet" href="../css/loadingScreen.css">
</head>
<html>
	<body>
        <div id="display">
             <div id="text">Uploading</div>
        </div>
	</body>
    <script type="text/javascript">
        var displayArea = document.getElementById("display");
        
            displayArea.style.height = String(window.innerHeight -20) + "px";
        
        window.addEventListener("resize", function(){
            displayArea.style.height = String(window.innerHeight -20) + "px";
        });
    </script>

<?php
    require 'database.php';

    $tablename = "videos";

    try {

            if(isset($_POST['submit'])){

                $name = $_FILES['fileToUpload']['name'];
                $temp = $_FILES['fileToUpload']['tmp_name'];

                $reportID = $_POST['reportID'];

                $message = '';
                switch( $_FILES['fileToUpload']['error'] ) {
                    case UPLOAD_ERR_OK:
                        $message = false;
                        break;
                    case UPLOAD_ERR_INI_SIZE:
                    case UPLOAD_ERR_FORM_SIZE:
                        $message .= ' - file too large (limit of xxx bytes).';
                        break;
                    case UPLOAD_ERR_PARTIAL:
                        $message .= ' - file upload was not completed.';
                        break;
                    case UPLOAD_ERR_NO_FILE:
                        $message .= ' - zero-length file uploaded.';
                        break;
                    default:
                        $message .= ' - internal error #'.$_FILES['newfile']['error'];
                        break;
                }


                move_uploaded_file($temp, __DIR__."/../uploaded/".$name);
                $url = "uploaded/$name";

                $query = $db->prepare("UPDATE Report SET VideoURL=:url WHERE ReportID=:reportID");
                $query->execute(array(
                    ":url" => $url,
                    ":reportID" => $reportID
                ));

                echo "<br/>".$name." has been uploaded<br/>";

            } 
            else {
                echo "<br />Please upload a file<br/>";
            }
        }
        catch(PDOException $e) {
            echo "Connection failed: " . $e->getMessage();
        }
        catch(Warning $warn) {
            echo $warn->getMessage();
        }
            
?>

<script>window.location='../index.php'</script>
    
</html>