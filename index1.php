<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="keywords" content="Ladybird, SMIS, school management system, ladybird school management information system">
    <meta name="description" content="The number one online School information system that provides solutions to 100s of schools and makes them go digital">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ladybird SMIS</title>
    <link rel="shortcut icon" href="images/ladybird.png" type="image/x-icon">
    <link rel="stylesheet" href="mainpage.css">
    <link rel="stylesheet" href="/sims/assets/CSS/font-awesome/css/all.css">
</head>
<body>
    <div class="mainpage">
        <div class="top_bar">
            <div class="headings">
                <img src="images/ladybird.png" alt="Ladybird Logo">
                <h3 style='color:black;'>Ladybird SMIS</h3>
            </div>
            <div class="headings">
                <menu id='hom-sch-btn'><i class="fa fa-home"></i> Home</menu>
                <menu id='login-sch-btn'><i class="fa fa-sign-in" aria-hidden="true"></i> Login</menu>
                <menu id="reg-sch"><i class="fa fa-register"></i> Register</menu>
                <menu id="aboutladybird"><i class="fa fa-register"></i> About Us</menu>
                <menu id="developer"><i class="fa fa-code"></i></menu>
            </div>
        </div>
        <div class="ladybird win "  id="home-paged">
            <div class="information_section animate">
                <div class="cont t-center-align">
                    <img src="images/login.jpg" alt="Login Image">
                </div>
            </div>
            <div class = "information_section  animate t-center-align">
                <h3>Are you a <b>user ?</b></h3>
                <p>The all-in-one school management information system with a suite of portals for parents, students and staff, giving your school full control of all academic, financial  and administrative information.</p>
                <div class="conts">
                    <button type="button"  id="logged_ind"><span class='overs'></span> Sign in</button>
                </div>
            </div>
        </div>
        <div class="ladybird win hide"   id="register_school">
            <div class="information_section animate inside">
                <div class="cont t-center-align">
                    <img src="images/regitration.jpg" alt="Login Image">
                </div>
            </div>
            <div class="information_section animate">
                <div class="cont t-center-align">
                    <h3>Join us today?</h3>
                    <p>Enter your details below and we will respond to you soon!</p>
                </div>
                <form class="YourDetail" id="your-details-id">
                    <div class="cont">
                        <label for="firstName">First Name: <br></label>
                        <input type="text" class="det-s" name="firstName" id="firstName" placeholder="First Name">
                    </div>
                    <div class="cont">
                        <label for="lastName">Last Name: <br></label>
                        <input type="text"  class="det-s" name="lastName" id="lastName" placeholder="Last Name">
                    </div>
                    <div class="cont">
                        <label for="p-number">Phone number: <br></label>
                        <input type="text"  class="det-s" name="p-number" id="p-number" placeholder="Phone Number">
                    </div>
                    <div class="cont">
                        <label for="email_addr">Email Address: <br></label>
                        <input type="email" class="det-s"  name="email_addr" id="email_addr" placeholder="Email Address">
                    </div>
                    <div class="cont">
                        <label for="sch_name">School Name: <br></label>
                        <input type="text"  class="det-s" name="sch_name" id="sch_name" placeholder="School Name">
                    </div>
                    <div class="cont">
                        <label for="sch_type">School Type: <br></label>
                        <select name="sch_type" class="det-s"  id="sch_type">
                            <option value="" hidden>Select School</option>
                            <option value="pri-sch">Primary School</option>
                            <option value="sec-sch">Secondary School</option>
                            <option value="low-sch">Lower Primary</option>
                        </select>
                    </div>
                    <div class="cont">
                        <p id="check_blanks"></p>
                    </div>
                    <div class="cont dp-flex-al-ctr">
                        <p class="link" id="home-return">Return to homepage</p>
                        <button type="button" class="submit-btn" id="submit-btn">Submit</button>
                    </div>
                </form>
            </div>
        </div>
        <div class="ladybird win hide" id="about-us">
            <div class="titles animate">
                <h3>Know more about us</h3>
                <div class="content">
                    <a href="#intro"><i class="fas fa-check"></i> Who are we?</a>
                    <a href="#matters"><i class="fas fa-check"></i> What Matters to us?</a>
                    <!-- <a href="#services">Our services ?</a> -->
                    <a href="#why-us"><i class="fas fa-check"></i> Why choose us</a>
                    <a href="#contact-us"><i class="fas fa-check"></i> Interested? contact us</a>
                    <!-- <a href="#faqs"><i class="fas fa-check"></i> Frequent asked question</a> -->
                </div>
            </div>
            <div class="titles animate">
                <div class="conts">
                    <h3>ABOUT US</h3>
                </div>
                <div class="contented">
                    <div class="inside-information" id="intro">
                        <div class="titled">
                            <p>Who are we ?</p>
                        </div>
                        <div class="inform">
                            <p>We are a software development company working hard with a mission to give solutions to 100s of schools to get digital and help them accomplish their mission and vision through it's easy to use and very advance all-in-one School Management software.</p>
                            <p>It started with only two students in 2020 who conceptualized the idea to currently a team of full-time professional working and giving service our clients across kenya.</p>
                        </div>
                    </div>
                    <div class="inside-information" id="matters">
                        <div class="titled">
                            <p>Who matters to us?</p>
                        </div>
                        <div class="inform">
                            <p>Since the beginning, we have always thought of becoming the best School ERP company which serves our customers. We have always wanted to bring the best of technology that could offer to bring all the stakeholders and staff of the institution on a single platform and operate the entire organization function in the most productive and efficient way.</p>
                        </div>
                    </div>
                    <div class="inside-information" id="why-us">
                        <div class="titled">
                            <p>Who choose us?</p>
                        </div>
                        <div class="inform">
                            <p>We us a team of developers have set priorities to these three things.</p>
                            <h4>1. Security</h4>
                            <p>-We take security very seriously so we have developed a comprehensive set of practices, technologies and policies to help ensure your data is secure.These services <i>to mention a few</i> include use of AWS and Microsoft AZURE technologies to enhance our security.</p>
                            <h4>2. Expert Team</h4>
                            <p>Robust softwares could not be made without Expert team. We have experienced analysts,  trained developers , domain experts & customer oriented Support team.</p>
                            <h4>3. Dedicated Support</h4>
                            <p>WE value our customers and best customer experience is our number one priority, We have customer oriented Support team for better support experience.</p>
                        </div>
                    </div>
                    <div class="inside-information" id="contact-us">
                        <div class="titled">
                            <p>We would love to hear from you.</p>
                        </div>
                        <div class="inform">
                            <p><i class="fas fa-phone-alt"></i> <a href="tel:254743551250"> +254743551250</a></p>
                            <p><i class="fab fa-facebook"></i> <a href="https://facebook.com">Facebook</a></p>
                            <p><i class="fab fa-twitter"></i> <a href="https://twitter.com">Twitter</a></p>
                            <p><i class="fas fa-paper-plane"></i><a href="mailto:hilaryme45@gmail.com"> Click to send us a mail</a></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="loadwindow hide" id="loadings">
            <div class="loadingcontents">
                <img src="images/load2.gif" alt="loading">
            </div>
        </div>
        <div class="footer">
            <p>Copyright © LadyBird School MIS 2020  - <?php echo date("Y");?></p>
        </div>
    </div>
    <script src="assets/JS/functions.js"></script>
    <script src="assets/JS/index.js"></script>
</body>
</html>
