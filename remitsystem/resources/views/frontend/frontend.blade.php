@include('frontend.includes.header')

    <!-- Banner section start -->
    <section class="banner v2">
      <div class="container">
        <div class="row">
          <div class="col-lg-7 col-md-12">
            <div class="ban-content">
              <span class="tagline">Simple and Easy Way to</span>
              <h1>Transfer Money Across World in Real Time <br>With No Charge</h1>
              <p>{{$application_obj->name}} Services is committed to providing more professional, cost effective foreign exchange and fund transfer services to customers by integrating its advanced business practices and strong IT resources. Technology is rooted in every aspect of the business of {{$application_obj->name}} Services. </p>
              <a href="#" class="btn btn-outline btn-round"><span class="bh"></span> <span><i class="fas fa-thumbs-up"> Like Us</span></a></i>
            </div>
          </div>
          <div class="col-lg-5 col-md-12">
            <form class="currency-form" action="#">
              <h4>Send Money</h4>
              <div class="form-field">
                <label>send amount</label>
                <div class="join-field">
                  <input type="text" name="send_amount" placeholder="$1,000.00">
                  <div class="curr-select">
                    <span class="selected"><img src="{{asset('images/flags/australia.png')}}" alt="">aud &nbsp;
                  </div>
                </div>
              </div>
              <div class="form-field">
                <label for="rcv_country">Receiver Country</label>
                <select id="rcv_country" name="rcv_country">
                  <option>Select Country</option>
                  <option value="usa">USA</option>
                  <option value="canada">Canada</option>
                  <option value="australia">Australia</option>
                </select>
              </div>
              <div class="form-field">
                <label for="del_type">Delivery Type</label>
                <select id="del_type" name="del_type">
                  <option>Choose Delivery Type</option>
                  <option value="imt">Immediate Transfer</option>
                  <option value="rtt">Real Time Transfer</option>
                  <option value="eft">Electronic Transfer</option>
                </select>
              </div>
                <button class="btn btn-block btn-filled form-btn"> <span class="bh"></span> <span>Continue Transition</span><i class="fas fa-arrow-right"></i></button>
              <span class="accept-terms">By clicking continue, i am agree with <a href="#">Terms &amp; Policy</a></span>
            </form>
          </div>
        </div>
      </div>
    </section>
    <!-- Banner section end -->



    <!-- Home send money section start -->
    <section class="home-send-money bg-offwhite">
      <div class="container">
        <div class="row">
          <div class="col-lg-8 col-md-10 m-auto">
            <div class="sec-heading">
              <span class="tagline">Our Process</span>
              <h2 class="sec-title">Transfer Money Fast and Easily</h2>
              <p class="sec-subtitle">Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Quis ipsum suspendisse ultrices gravida.</p>
            </div>
          </div>
        </div>
        <div class="row align-items-md-center">
          <div class="col-md-5">
            <div class="mbl-screen">
              <!-- <figure class="mbl-scrn1">
                <img src="images/mbl-scrn1.png" alt="">
              </figure>
              <figure class="mbl-scrn2">
                <img src="images/mbl-scrn2.png" alt="" >
              </figure> -->
              <img src="{{asset('assets/images/home/3.png')}}" alt="" class="an1 animg3" >
                <img src="{{asset('assets/images/an1/5.png')}}" alt="" class="an1 animg5" >
                <img src="{{asset('assets/images/an1/4.png')}}" alt="" class="an1 animg4" >
            </div>
          </div>
          <div class="col-md-6 offset-md-1">
            <div class="content-box">
              <span class="tagline bar">FAst Transfer</span>
              <h2>Send Money Anytime, Anywhere In a Minute</h2>
              <p>{{$application_obj->name}} Remote Trader and {{$application_obj->name}} Online, the leading-edge real-time foreign exchange solutions developed by in-house resources, provides an electronic and automatic way to manage daily business. The advanced systems ensure and secure the safety of customer transactions and information, and what's more, highly improve our performance and make it possible for us to provide our valuable customers with the most competitive rates even seen in the market. {{$application_obj->name}} Serivces ensures to provide fast, easy and reliable remittance facility.</p>
              <a href="#" class="btn btn-warning"><span class="bh"></span> <span>Send Money</span></a>
            </div>
          </div>
        </div>
      </div>
    </section>
    <!-- Home send money section end -->

    <!-- Services section start -->
    <section class="services">
      <div class="container-fluid">
        <div class="row">
          <div class="col-lg-8 col-md-10 m-auto">
            <div class="sec-heading">
              <span class="tagline">Our Features </span>
              <h2 class="sec-title">Why Choose Us?</h2>
              <p class="sec-subtitle">Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Quis ipsum suspendisse ultrices gravida.</p>
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-md-3">
            <div class="iconBox boxed text-center">
               <img src="{{asset('assets/images/home/mobile.svg')}}" alt="" class="img-icon">

              <h5><a href="#">Simple</a></h5>
              <p>It’s easy to use and we’re here to help round the clock</p>
            </div>
          </div>
          <div class="col-md-3">
            <div class="iconBox boxed text-center">
             <img src="{{asset('assets/images/home/spaceship.svg')}}" alt="" class="img-icon">


              <h5><a href="#">Fast</a></h5>
              <p>We can help you to transfer your money in quick time, no delay. Normally, we transfer money in 1-2 days</p>
            </div>
          </div>
          <div class="col-md-3">
            <div class="iconBox boxed text-center">
               <img src="{{asset('assets/images/home/piggybank.svg')}}" alt="" class="img-icon">



              <h5><a href="#">Low Cash</a></h5>
              <p>Our low fees and exchange rates are shown upfront</p>
            </div>
          </div>
          <div class="col-md-3">
              <div class="iconBox boxed text-center">
                <img src="{{asset('assets/images/home/twohands.svg')}}" alt="" class="img-icon">



                <h5><a href="#">Highest Exchange Rate</a></h5>
                <p>We are the one who offer the best exchange rate in town. For highest rate, please contact us.</p>
              </div>
            </div>
        </div>
      </div>
    </section>
    <!-- Services section end -->

   @include('frontend.includes.footer')
