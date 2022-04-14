<?php
  $email = isset($_SESSION["email"]) ? $_SESSION["email"] : "agent@support.com";
  $role = isset($_SESSION["role"]) ? $_SESSION["role"] : "HomeHero Support";
  $roleTypes = ["Verification Support","Customer Support","Technical Support","Supervisor","Admin","Super Admin"];

  if($role < 0 || $role > 6){
    $role = null;
  }
?>
<nav class="navbar navbar-dark sticky-top bg-dark flex-md-nowrap justify-content-start">
    <div class="d-flex nametag ">
        <div class="img-container d-flex  align-items-center p-0 m-0">
            <a class="navbar-brand col-sm-3 col-md-2 mr-0" href="#">
                <img src="<?php echo $level;?>/images/logo/HH_Logo_Mobile.svg" class="rounded mr-2" alt="Home Hero Logo" id="header-logo-mobile">
            </a>
        </div>
         <div class="text-white text-container d-flex flex-column justify-content-center ">
            <h6 class="p-0 m-0"><?php echo htmlentities($email);?></h6>
            <p class="p-0 m-0 nametag-text"><?php echo $role == null ? "HomeHero Support":$roleTypes[$role-1];?></p>
        </div>
    </div>

  <form class="form-inline ml-5">
    <input class="form-control mr-sm-2" type="search" placeholder="Enter Ticket No." aria-label="Search">
    <button class="btn btn-outline-light my-2 my-sm-0" type="submit">Search</button>
  </form>
</nav>