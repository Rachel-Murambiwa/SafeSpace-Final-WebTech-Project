<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="../assets/css/register.css">
  <title>Register - SafeSpace</title>
</head>
<body class="register-body">
  
  <div class="register-container">
    
    <div class="register-visual" style="background: url('../assets/images/heal3.jpeg') no-repeat center; background-size: cover;">
      <div class="register-visual-text">
        <h2>Join SafeSpace Today</h2>
        <p>Together, we heal and grow.</p>
      </div>
    </div>

    <div class="register-form-container">
      <form action="../actions/register_user.php" method="POST" class="register-form">
        <h2 class="form-title">Create Your Account</h2>
        <p class="form-subtitle" style="margin-bottom: 20px; color: #666;">Please fill in your details to join</p>

        <div class="form-row">
            <div class="input-group">
              <label for="fname">First Name</label>
              <input type="text" id="fname" name="fname" placeholder="First Name" required>
            </div>
    
            <div class="input-group">
              <label for="lname">Last Name</label>
              <input type="text" id="lname" name="lname" placeholder="Last Name" required>
            </div>
        </div>

        <div class="input-group">
          <label for="country">Country</label>
          <select id="country" name="country" required>
              <option value="" disabled selected>Select your country</option>
              <option value="Algeria">Algeria</option>
              <option value="Angola">Angola</option>
              <option value="Benin">Benin</option>
              <option value="Botswana">Botswana</option>
              <option value="Burkina Faso">Burkina Faso</option>
              <option value="Burundi">Burundi</option>
              <option value="Cabo Verde">Cabo Verde</option>
              <option value="Cameroon">Cameroon</option>
              <option value="Central African Republic">Central African Republic</option>
              <option value="Chad">Chad</option>
              <option value="Comoros">Comoros</option>
              <option value="Cote d'Ivoire">Cote d'Ivoire</option>
              <option value="Djibouti">Djibouti</option>
              <option value="DRC">DRC</option>
              <option value="Egypt">Egypt</option>
              <option value="Equatorial Guinea">Equatorial Guinea</option>
              <option value="Eritrea">Eritrea</option>
              <option value="Eswatini">Eswatini</option>
              <option value="Ethiopia">Ethiopia</option>
              <option value="Gabon">Gabon</option>
              <option value="Gambia">Gambia</option>
              <option value="Ghana">Ghana</option>
              <option value="Guinea">Guinea</option>
              <option value="Guinea-Bissau">Guinea-Bissau</option>
              <option value="Kenya">Kenya</option>
              <option value="Lesotho">Lesotho</option>
              <option value="Liberia">Liberia</option>
              <option value="Libya">Libya</option>
              <option value="Madagascar">Madagascar</option>
              <option value="Malawi">Malawi</option>
              <option value="Mali">Mali</option>
              <option value="Mauritania">Mauritania</option>
              <option value="Mauritius">Mauritius</option>
              <option value="Morocco">Morocco</option>
              <option value="Mozambique">Mozambique</option>
              <option value="Namibia">Namibia</option>
              <option value="Niger">Niger</option>
              <option value="Nigeria">Nigeria</option>
              <option value="Rwanda">Rwanda</option>
              <option value="Sao Tome and Principe">Sao Tome and Principe</option>
              <option value="Senegal">Senegal</option>
              <option value="Seychelles">Seychelles</option>
              <option value="Sierra Leone">Sierra Leone</option>
              <option value="Somalia">Somalia</option>
              <option value="South Africa">South Africa</option>
              <option value="South Sudan">South Sudan</option>
              <option value="Sudan">Sudan</option>
              <option value="Tanzania">Tanzania</option>
              <option value="Togo">Togo</option>
              <option value="Tunisia">Tunisia</option>
              <option value="Uganda">Uganda</option>
              <option value="Zambia">Zambia</option>
              <option value="Zimbabwe">Zimbabwe</option>
          </select>
        </div>

        <div class="input-group">
          <label for="email">Email Address</label>
          <input type="email" id="email" name="email" placeholder="jane@example.com" required>
        </div>

        <div class="input-group">
          <label for="password">Password</label>
          <input type="password" id="password" name="password_hash" placeholder="Create a strong password" required>
          <ul id="password-requirements">
            <li id="req-length" class="invalid">At least 8 characters</li>
            <li id="req-upper" class="invalid">One uppercase letter (A-Z)</li>
            <li id="req-lower" class="invalid">One lowercase letter (a-z)</li>
            <li id="req-number" class="invalid">One number (0-9)</li>
            <li id="req-special" class="invalid">One special character (!@#$...)</li>
          </ul>
        </div>

        <div class="input-group">
          <label for="confirm-password">Confirm Password</label>
          <input type="password" id="confirm-password" name="confirm_password" placeholder="Repeat password" required>
          <small id="match-message" style="display: block; margin-top: 5px; font-weight: bold;"><small>
        </div>

        <button type="submit" class="btn-submit">Register</button>
        <p id="success-message"></p>
        
        <p class="auth-footer" style="margin-top: 20px; text-align: center;">
            Already have an account? <a href="login.php" 
            style="color: #2A7F86; font-weight: bold;">Login here</a>
        </p>
      </form>
    </div>
  </div>
<script src = "../assets/js/register.js"></script>
<?php include "../utils/exit_button.php" ?>
</body>
</html>