<?php

   include 'php/connection.php';
   session_start();



?>
<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <link rel="icon" type="image/x-icon" href="icons/patient/toothlogos.png">


   <link rel="stylesheet" href="css/index.css">
   <title>seek4smiles Dental Clinic</title>

   <!-- swiper css link  -->
   <link rel="stylesheet" href="https://unpkg.com/swiper@8/swiper-bundle.min.css"/>

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">

   <!-- custom css file link  -->
   <link rel="stylesheet" href="css/style.css">

</head>
<body>
   
<!-- header section starts  -->

<section class="header">

   <a href="#" class="logo"><img src="icons/patient/seek4smilesLogo.png" alt=""></a>

   <nav class="navbar">
      <a href="index.php">Home</a>
      <a href="#swiper home-slider">Services</a>
      <a href="#service-home">Info</a>
      <a href="#footer">About Us</a>
      <a href="login.php">Log in | Sign up</a>

   </nav>

   <div id="menu-btn" class="fas fa-bars"></div>

</section>

<!-- header section ends -->

<!-- home section starts  -->

<section class="home">

   <div class="swiper home-slider" id="swiper home-slider">

      <div class="swiper-wrapper">

         <div class="swiper-slide slide" style="background:url(icons/patient/Dental-Imaging-lowrez-1-scaled.jpg) no-repeat">
            <div class="content">
               <span>your smile, our priority</span>
               <h3>make your teeth healthy</h3>
               <a href="#" class="btn">discover more</a>
            </div>
         </div>

         <div class="swiper-slide slide" style="background:url(icons/patient/tooth-cleaning.jpg) no-repeat">
            <div class="content">
               <span>your smile, our priority</span>
               <h3>make your teeth healthy</h3>
               <a href="#" class="btn">discover more</a>
            </div>
         </div>

         <div class="swiper-slide slide" style="background:url(icons/patient/AdobeStock_141940713-scaled.jpeg) no-repeat">
            <div class="content">
               <span>your smile, our priority</span>
               <h3>make your teeth healthy</h3>
               <a href="#" class="btn">discover more</a>
            </div>
         </div>
         
      </div>

      <div class="swiper-button-next"></div>
      <div class="swiper-button-prev"></div>

   </div>

</section>

<!-- home section ends -->

<!-- services section starts  -->

<div class="specialization-container">
    <div class="specialization-item">
        <div class="image-overlay">
            <img src="icons/patient/Orthodontics.png" alt="Specialization 1">
            <div class="overlay"></div>
        </div>
        <p>Orthodontics</p>
    </div>
    <div class="specialization-item">
        <div class="image-overlay">
            <img src="icons/patient/periodontics.png"alt="Specialization 2">
            <div class="overlay"></div>
        </div>
        <p>Periodontics</p>
    </div>
    <div class="specialization-item">
        <div class="image-overlay">
            <img src="icons/patient/endodontics.png" alt="Specialization 3">
            <div class="overlay"></div>
        </div>
        <p>Endodontics</p>
    </div>
    <div class="specialization-item">
        <div class="image-overlay">
            <img src="icons/patient/peduatruc.png" alt="Specialization 4">
            <div class="overlay"></div>
        </div>
        <p>Pediatric Dentistry</p>
    </div>
    <div class="specialization-item">
        <div class="image-overlay">
            <img src="icons/patient/oral.png" alt="Specialization 4">
            <div class="overlay"></div>
        </div>
        <p>Oral and Maxilloficial Surgery</p>
    </div>
</div>

<!-- services section ends -->

<!-- service about about section starts  -->
<div class="service-home" id="service-home">
   <section class="home-about" >

      <div class="image">
         <img src="icons/patient/Orthodontics.png" alt="">
      </div>
   
      <div class="content">
         <h3>Orthodontics</h3>
         <p>Orthodontics is a specialized branch of dentistry that addresses the alignment of teeth and jaws. Orthodontists use various devices, such as braces, clear aligners, and retainers, to correct issues like crooked teeth, overbites, underbites, and jaw misalignments. The primary goals of orthodontic treatment are to improve oral function, enhance aesthetics, and ensure long-term dental health.</p>
         <a href="#" class="btn">read more</a>
      </div>
   
   </section>
   
   <section class="home-about">
   
      <div class="image">
         <img src="icons/patient/periodontics.png" alt="">
      </div>
   
      <div class="content">
         <h3>Periodontics</h3>
           <p>Periodontics is a specialized branch of dentistry that focuses on the prevention, diagnosis, and treatment of periodontal (gum) disease, as well as the placement of dental implants. Periodontists are experts in managing oral inflammation and treating conditions that affect the gums and supporting structures of the teeth.</p>
         <a href="#" class="btn">read more</a>
      </div>
   
   </section>
   
   <section class="home-about">
   
      <div class="image">
         <img src="icons/patient/endodontics.png" alt="">
      </div>
   
      <div class="content">
         <h3>Endodontics</h3>
           <p>Endodontics is a specialized field of dentistry that focuses on the diagnosis, prevention, and treatment of diseases and injuries of the dental pulp and the tissues surrounding the roots of a tooth. Endodontists are experts in managing tooth pain and performing procedures to save teeth that might otherwise need to be extracted.</p>
         <a href="#" class="btn">read more</a>
      </div>
   
   </section>
   
   <section class="home-about">
   
       <div class="image">
          <img src="icons/patient/peduatruc.png" alt="">
       </div>
    
       <div class="content">
          <h3>Pediatric Dentistry</h3>
           <p>Pediatric dentistry is a specialized branch of dentistry dedicated to the oral health of children from infancy through the teenage years. Pediatric dentists are trained to care for a child's teeth, gums, and mouth throughout the various stages of childhood.</p>
          <a href="#" class="btn">read more</a>
       </div>
    
    </section>
</div>



 <section class="home-about">

    <div class="image">
       <img src="icons/patient/oral.png" alt="">
    </div>
 
    <div class="content">
       <h3>Oral and Maxilloficial Surgery</h3>
        <p>Oral and maxillofacial surgery is a specialized field of dentistry that focuses on the diagnosis and surgical treatment of diseases, injuries, and defects involving the mouth, teeth, jaws, and face. Oral and maxillofacial surgeons are trained to perform a wide range of procedures that address both functional and aesthetic concerns.</p>
       <a href="#" class="btn">read more</a>
    </div>
 
 </section>
 

<!-- home packages section ends -->

<!-- home offer section starts  -->


    <div class="slogan-container">
        <div class="slogan-content">
            <div class="slogan-h3">
                <h3>Book an appointment up to 50% off</h3>
            </div>
            <div class="slogan-p">
                <p>Book anytime, anywhere.</p>
            </div>
            <a href="login.html" class="btn">book now</a>
        </div>
    </div>

<!-- home offer section ends -->

<!-- footer section starts  -->

<section class="footer" id="footer" style="footer{background: url(icons/patient/background.png) no-repeat}">

   <div class="box-container">

    <div class="box">
        <h3>quick links</h3>
        <a href="#"> <i class="fas fa-angle-right" style="color: rgb(205, 106, 48);"></i> Home</a>
        <a href="#"> <i class="fas fa-angle-right" style="color: rgb(205, 106, 48);"></i> Services</a>
        <a href="#"> <i class="fas fa-angle-right" style="color: rgb(205, 106, 48);"></i> Info</a>
        <a href="#"> <i class="fas fa-angle-right" style="color: rgb(205, 106, 48);"></i> About us</a>
    </div>
    
    <div class="box">
        <h3>extra links</h3>
        <a href="#"> <i class="fas fa-angle-right" style="color: rgb(205, 106, 48);"></i> ask questions</a>
        <a href="#"> <i class="fas fa-angle-right" style="color: rgb(205, 106, 48);"></i> about us</a>
        <a href="#"> <i class="fas fa-angle-right" style="color: rgb(205, 106, 48);"></i> privacy policy</a>
        <a href="#"> <i class="fas fa-angle-right" style="color: rgb(205, 106, 48);"></i> terms of use</a>
    </div>
    
    <div class="box">
        <h3>contact info</h3>
        <a href="#"> <i class="fas fa-phone" style="color: rgb(205, 106, 48);"></i> +123-456-7890 </a>
        <a href="#"> <i class="fas fa-phone" style="color: rgb(205, 106, 48);"></i> +111-222-3333 </a>
        <a href="#"> <i class="fas fa-envelope" style="color: rgb(205, 106, 48);"></i> seek4smiles@gmail.com </a>
        <a href="#"> <i class="fas fa-map" style="color: rgb(205, 106, 48);"></i> Manila, philippines - 400104 </a>
    </div>
    
    <div class="box">
        <h3>follow us</h3>
        <a href="#"> <i class="fab fa-facebook-f" style="color: rgb(205, 106, 48);"></i> facebook </a>
        <a href="#"> <i class="fab fa-twitter" style="color: rgb(205, 106, 48);"></i> twitter </a>
        <a href="#"> <i class="fab fa-instagram" style="color: rgb(205, 106, 48);"></i> instagram </a>
        <a href="#"> <i class="fab fa-linkedin" style="color: rgb(205, 106, 48);"></i> linkedin </a>
    </div>

   </div>

   <div class="credit"> created by <span>GROUP FOURANDSEEKS : Alliza Era, Earl Mendilio, Patosa Mykie, Polison Miguel</span> | all rights reserved! </div>

</section>
<!-- footer section ends -->


<!-- swiper js link  -->
<script src="https://unpkg.com/swiper@8/swiper-bundle.min.js"></script>

<!-- custom js file link  --->

<!-- Initialize Swiper -->
<script>
    const swiper = new Swiper('.swiper', {
      // Loop through slides
      loop: true,
  
      // Enable navigation arrows
      navigation: {
        nextEl: '.swiper-button-next',
        prevEl: '.swiper-button-prev',
      },
  
      // Enable autoplay
      autoplay: {
        delay: 5000, // Time between slides (ms)
        disableOnInteraction: false,
      },
  
      // Slides Per View and Space Between
      slidesPerView: 1, // Full-screen single slide
      spaceBetween: 0,
    });
  </script>

</body>
</html>