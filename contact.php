 

<?php

// define variables and set to empty values
$nameErr = $emailErr = $commentErr = "";
$name = $email = $comment = "";
$successMsg = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

  $canSubmit = true;

  if (empty($_POST["name"])) {
    $nameErr = "Name is required";
    $canSubmit = false;
  } else {
    $name = testUserInput($_POST["name"]);
    // check if name only contains letters and whitespace
    if (!preg_match("/^[a-zA-Z ]*$/",$name)) {
      $nameErr = "Only letters and white space allowed";
      $canSubmit = false;
    }
  }
  
  if (empty($_POST["email"])) {
    $emailErr = "Email is required";
    $canSubmit = false;
  } else {
    $email = testUserInput($_POST["email"]);
    // check if e-mail address is well-formed
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
      $emailErr = "Invalid email format";
      $canSubmit = false;
    }
  }
    
  if (empty($_POST["comment"])) {
    $commentErr = "Message is required";
    $canSubmit = false;
  } else {
    $comment = testUserInput($_POST["comment"]);
  }

  // if all checks pass, send the email
  if($canSubmit){

    $to = 'myemail@email.com';
    $subject = 'Enquiry from '.$name;
    $message = wordwrap($comment, 70);
    $body = '<h2>Contact Request</h2>
            <h4>Name:</h4><p>'.$name.'</p>
            <h4>Email:</h4><p>'.$email.'</p>
            <h4>Message:</h4><p>'.$message.'</p>';
    
    // custom messages for email sent / email failed
    if (mail($to, $subject, $body)){
      $successMsg = "Thank you for your message!";
      $msgClass = "alert-success";
      
      //reset form
      $nameErr = $emailErr = $commentErr = "";
      $name = $email = $comment = "";
      
    }else
    {
     $successMsg = "An error occurred, please try again later";
     $msgClass = "alert-danger";
    }

  }
}


// checks for input data
function testUserInput($data) {
  $data = trim($data);
  $data = stripslashes($data);
  $data = htmlspecialchars($data);
  return $data;
}

?>


<form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
    <div class="form-content">
      <p class="<?php echo $msgClass;?>" ><?php echo $successMsg;?></p>

        <div class="single-field">
            <label for="fname" class="label">First Name*</label>
            <span class="error"><?php echo $nameErr;?></span>
            <input type="text" id="fname" class="input" name="name" value="<?php echo $name;?>">

        </div>
        <div class="single-field">
            <label for="email" class="label">Email*</label>
            <span class="error"><?php echo $emailErr;?></span>
            <input type="text" id="email" class="input" name="email" value="<?php echo $email;?>">
        </div>
        <div class="single-field">
            <label for="message" class="label">Message*</label>
            <span class="error"><?php echo $commentErr;?></span>
            <textarea name="comment" id="message" class="input" rows="10"><?php echo $comment;?></textarea>
        </div>

        <input type="submit" name="submit" class="button-submit" value="Submit">
    </div>
</form>



