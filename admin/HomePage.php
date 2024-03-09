<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>MovieMagic | Where Every Frame Tells A Story</title>
    <link rel="website icon" type="JPG" href="#">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@8/swiper-bundle.min.css">
    <style>
      *{
        margin: 0;
        padding: 0;
        color: #f2f5f7;
        box-sizing: border-box;
        font-family: "Open Sans", sans-serif;
        letter-spacing: 1px;
        font-weight: 300;
      }
      header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 1rem;
        background-color: #131418;
      }
      body{
        overflow-x: hidden;
        background-color: #131418;
        overflow-y: scroll;
      }
      nav{
        height: 4.5rem;
        width: 100vw;
        background-color: #131418;
        box-shadow: 0 3px 20px rgba(0, 0, 0, 0.2);
        display: flex;
        position: fixed;
        z-index: 10;
      }
      .logo{
        padding:1vh 1vw;
        text-align: center;
      }
      .logo img {
        height: 5rem;
        width: 5rem;
      }
      .nav-links{
        display: flex;
        list-style: none;
        width: 88vw;
        padding: 0 0.7vw;
        justify-content: space-evenly;
        align-items: center;
        text-transform: uppercase;
      }
      .nav-links li a{
        text-decoration: none;
        margin: 0 0.7vw;
        border-bottom: 2px solid transparent;
        transition: color 0.3s;
        border-bottom: 0.3s;
      }
      .nav-links li a:hover {
        color: #61DAFB;
        border-bottom: 2px solid #61DAFB;
      }
      .nav-links li {
        position: relative;
        margin: 0 1rem;
      }
      .nav-links li a:hover::before{
        width: 80%;
      }
      .search-bar form {
        display: flex;
        align-items: center;
        background-color: #232323;
        padding: 0.5rem;
        border-radius: 5px;
      }

      .search-bar input {
        border: none;
        background: transparent;
        color: #fff;
        font-size: 16px;
        width: 200px; 
        margin-right: 10px;
      }

      .search-bar input:focus {
        outline: none;
      }

      .search-bar button {
        background: #61DAFB;
        border: none;
        color: #fff;
        cursor: pointer;
        padding: 0.3rem 0.5rem;
        border-radius: 3px;
      }

      .search-bar button ion-icon {
        font-size: 20px;
      }

      .dropdown {
        position: relative;
      }

      .dropdown-content {
        display: none;
        position: absolute;
        min-width: 100px;
        box-shadow: 0 3px 20px rgba(0, 0, 0, 0.2);
        z-index: 1;
        top: 1.5rem;
        left: 0;
        color: #f2f5f7;
        background-color: black;
        transition: color 0.3s;
        list-style: none;
      }

      .dropdown-content a {
        color: #f2f5f7;
        padding: 12px 16px;
        text-decoration: none;
        display: block;
        transition: color 0.3s;
        text-align: left;
      }
      .search-bar{

      }
      .dropdown-content a:hover {
        color: #131418;
      }

      .dropdown:hover .dropdown-content {
        display: block;
      }
      main {
        position: relative;
        width: calc(min(90rem, 90%));
        margin: 0 auto;
        min-height: 100vh;
        column-gap: 3rem;
        padding-block: min(20vh, 3rem);
      }

      .bg {
        position: fixed;
        top: -4rem;
        left: -12rem;
        z-index: -1;
        opacity: 0;
      }

      .bg2 {
        position: fixed;
        bottom: -2rem;
        right: -3rem;
        z-index: -1;
        width: 9.375rem;
        opacity: 0;
      }

      main > div span {
        text-transform: uppercase;
        letter-spacing: 1.5px;
        font-size: 1rem;
        color: #717171;
      }

      main > div h1 {
        text-transform: capitalize;
        letter-spacing: 0.8px;
        font-family: "Roboto", sans-serif;
        font-weight: 900;
        font-size: clamp(3.4375rem, 3.25rem + 0.75vw, 4rem);
        background-color: #005baa;
        background-image: linear-gradient(45deg, #005baa, #000000);
        background-size: 100%;
        background-repeat: repeat;
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        -moz-background-clip: text;
        -moz-text-fill-color: transparent;
      }

      main > div hr {
        display: block;
        background: #005baa;
        height: 0.25rem;
        width: 6.25rem;
        border: none;
        margin: 1.125rem 0 1.875rem 0;
      }

      main > div p {
        line-height: 1.6;
      }

      main a {
        display: inline-block;
        text-decoration: none;
        text-transform: uppercase;
        color: #717171;
        font-weight: 500;
        background: #fff;
        border-radius: 3.125rem;
        transition: 0.3s ease-in-out;
      }

      main > div > a {
        border: 2px solid #c2c2c2;
        margin-top: 2.188rem;
        padding: 0.625rem 1.875rem;
      }

      main > div > a:hover {
        border: 0.125rem solid #005baa;
        color: #005baa;
      }

      .swiper {
        width: 100%;
        padding-top: 3.125rem;
      }

      .swiper-pagination-bullet,
      .swiper-pagination-bullet-active {
        background: #fff;
      }

      .swiper-pagination {
        bottom: 1.25rem !important;
      }

      .swiper-slide {
        width: 18.75rem;
        height: 28.125rem;
        display: flex;
        flex-direction: column;
        justify-content: end;
        align-items: self-start;
      }

      .swiper-slide h2 {
        color: #fff;
        font-family: "Roboto", sans-serif;
        font-weight: 400;
        font-size: 1.4rem;
        line-height: 1.4;
        margin-bottom: 0.625rem;
        padding: 0 0 0 1.563rem;
        text-transform: uppercase;
      }

      .swiper-slide p {
        color: #dadada;
        font-family: "Roboto", sans-serif;
        font-weight: 300;
        padding: 0 1.563rem;
        line-height: 1.6;
        font-size: 0.75rem;
        display: -webkit-box;
        -webkit-line-clamp: 4;
        -webkit-box-orient: vertical;
        overflow: hidden;
      }

      .swiper-slide a {
        margin: 1.25rem 1.563rem 3.438rem 1.563rem;
        padding: 0.438em 1.875rem;
        font-size: 0.9rem;
      }

      .swiper-slide a:hover {
        color: #005baa;
      }

      .swiper-slide div {
        display: none;
        opacity: 0;
        padding-bottom: 0.625rem;
      }

      .swiper-slide-active div {
        display: block;
        opacity: 1;
      }
      .swiper-3d .swiper-slide-shadow-left,
      .swiper-3d .swiper-slide-shadow-right {
        background-image: none;
      }

      @media screen and (min-width: 48rem) {
        main {
          display: flex;
          align-items: center;
        }

        .bg,
      .bg2 {
          opacity: 0.1;
        }
      }
      @media screen and (min-width: 93.75rem) {
        .swiper {
          width: 85%;
        }
      }
      .swiper-wrapper .swiper-slide .swiper-slide--one{
        background-image: url("file:///C:/xampp/htdocs/MovieMagic/admin/ImagesandVideos/MoviePosters/kungfupanda4.jpg");
      }
    </style>
</head>
<body>
    <nav>
        <div class="logo" style="display: flex;align-items: center;">
         <span style="color:#01939c; font-size:26px; font-weight:bold; letter-spacing: 1px;margin-left: 20px;">MovieMagic</span>
        </div>
        <ul class="nav-links">
          <li><a href="file:///C:/xampp/htdocs/MovieMagic/HomePage.html#">Home</a></li>
          <li class="dropdown">
            <a href="#" class="dropdown-toggle">Genre <i class="fas fa-caret-down"></i></a>
            <ul class="dropdown-content">
              <li><a href="#" class="genre-link">Action</a></li>
              <li><a href="#" class="genre-link">Adventure</a></li>
              <li><a href="#" class="genre-link">Biography</a></li>
              <li><a href="#" class="genre-link">Comedy</a></li>
              <li><a href="#" class="genre-link">Documentary</a></li>
              <li><a href="#" class="genre-link">Drama</a></li>
              <li><a href="#" class="genre-link">Fantasy</a></li>
              <li><a href="#" class="genre-link">Horror</a></li>
              <li><a href="#" class="genre-link">Romance</a></li>
              <li><a href="#" class="genre-link">Sci-Fi</a></li>
              <li><a href="#" class="genre-link">Thriller</a></li>
            </ul>
          </li>
          <li><a href="#">Top IMdb</a></li>
          <li><a href="#">Movies</a></li>
          <li><a href="#">TV-Shows</a></li>
          <li class="search-bar">
            <form action="#">
              <input type="text" placeholder="Search">
              <button type="submit"><ion-icon name="search"></ion-icon></button>
            </form>
          </li>
        </ul>
    </nav>
    <main>

      <div class="swiper">
        <div class="swiper-wrapper">
          <div class="swiper-slide swiper-slide--one">
            <div>
              <h2 style="font-weight: bold; font-size: x-large;">Kung Fu Panda 4</h2>
              <p style="font-weight: bold; font-size: 95%;"> ⭐6.9 | 2024 |94 min | Animation | Adventure | Action<br></p>
              <p>After Po is tapped to become the Spiritual Leader of the Valley of Peace, he needs to find and train a new Dragon Warrior, while a wicked sorceress plans to re-summon all the master villains whom Po has vanquished to the spirit re...</p>
              <a href="#" target="_blank">Watch Now</a>
            </div>
          </div>
          <div class="swiper-slide swiper-slide--two">
            <div>
              <h2 style="font-weight: bold; font-size: x-large;">Dune: Part One</h2>
              <p style="font-weight: bold; font-size: 95%;"> ⭐8 | 2021 | 155 min | Drama | Adventure | Action<br></p>
              <p>A noble family becomes embroiled in a war for control over the galaxy's most valuable asset while its heir becomes troubled by visions of a dark future.</p>
              <a href="#" target="_blank">Watch Now</a>
            </div>
          </div>
    
          <div class="swiper-slide swiper-slide--three">
    
            <div>
              <h2 style="font-weight: bold; font-size: x-large;">Dune: Part Two</h2>
              <p style="font-weight: bold; font-size: 95%;"> ⭐9 | 2024 | 166 min | Drama | Adventure | Action <br></p>
              <p>Paul Atreides unites with Chani and the Fremen while seeking revenge against the conspirators who destroyed his family.</p>
              <a href="#" target="_blank">Watch Now</a>
            </div>
          </div>
    
          <div class="swiper-slide swiper-slide--four">
    
            <div>
              <h2 style="font-weight: bold; font-size: x-large;">Halo</h2>
              <p style="font-weight: bold; font-size: 95%;">⭐7.2 | 2022 |  Sci-Fi | Adventure | Action <br></p>
              <p>Aliens threaten human existence in an epic 26th-century showdown.</p>
              <a href="#" target="_blank">Watch Now</a>
            </div>
          </div>
    
          <div class="swiper-slide swiper-slide--five">
    
            <div>
              <h2 style="font-weight: bold; font-size: x-large;">The Gentlemen</h2>
              <p style="font-weight: bold; font-size: 95%;"> 2024 |  Comedy | Crime | Action <br></p>
              <p>Eddie Horniman inherits a family estate, initially unaware its home to a drug empire run by a syndicate that has no plans to vacate.</p>
              <a href="#" target="_blank">Watch Now</a>
            </div>
          </div>
        </div>
        <div class="swiper-pagination"></div>
      </div>
      
    </main>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Swiper/8.4.5/swiper-bundle.min.js"></script>
    <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
	  <script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>
    <script src="Homepage.js"></script>
</body>
</html>
