<section class="viewcar-section">

    <!-- CAR SECTION 1 -->
    <section class="d-flex" id="viewcar_section1">
    <div class="view-car-row d-flex">
    <div class="car-photo">

        <img src="/images/uploads/{{ $viewcar->carphoto }}"
        id="viewphoto-img" style="object-fit: cover;"/>
    </div>
            
    </div>

    <div class="car-info-row">

                    <div class="car-info-title">
                        <h3>{{ $viewcar->brand}} {{ $viewcar->model}} {{ $viewcar->year}}</h3>
                        <hr class="bg-dark">
                    </div>
                    

                    <div class="car-info-col d-flex">
                        <div class="car-price d-flex">
                            <h4><sup>₱ </sup>{{ number_format($viewcar->dailyrate) }} | Daily</h4>
                            <h4><sup>₱ </sup>{{ number_format($viewcar->weeklyrate) }} | Weekly</h4>
                            <h4><sup>₱ </sup>{{ number_format($viewcar->monthlyrate) }} | Monthly</h4>
                        </div>

                        
                        
                    </div>

                    <div class="viewcarbuttons">
                        <!-- <button type="button" class="btn-addcart">
                            <span><i class="far fa-cart-plus"></i></span>
                            <span>ADD TO CART</span>
                        </button> -->

                        <a href="/bookingforms/{{ $viewcar->slug }}" type="button" class="btn-checkout text-decoration-none">
                            <span><i class="fas fa-caret-right"></i></span>
                            <span>BOOK NOW</span>
                        </a>
                    </div>
                    

            </div>

    </section>

        <!-- CAR SECTION 2 -->
        <section id="viewcar_section2" class="pt-4">
                <h5><strong>Characteristics</strong></h5> 
                <hr class="bg-dark">

                <div class="viewcar_info d-flex">
                <div>      
                    <h6>Brand: {{ $viewcar->brand}}</h6>
                    <h6>Model: {{ $viewcar->model}}</h6>
                    <h6>Year-Model: {{ $viewcar->year}}</h6>
                    <h6>Type: {{ $viewcar->vehicle}}</h6>
                    <h6>Seats: {{ $viewcar->seats}}</h6>
                    
                </div>

                <div>
                    <h6>Fuel: {{ $viewcar->fuel}}</h6>
                    <h6>Displacement: {{ $viewcar->displacement}} cc</h6>
                    <h6>Color: {{ $viewcar->color}} km</h6>
                    <h6>Car Location: {{ $viewcar->carlocation}}</h6>
                    <h6>Transmission: {{ $viewcar->transmission}}</h6>
                </div>
                </div>

        </section>


        <!-- CAR SECTION 3 -->
        <section id="viewcar_section3" class="pt-4">
                <h5><strong>Requirements</strong></h5> 
                <hr class="bg-dark">
                <h6>1. Driver's license</h6>
                <h6>2. Pay before drive</h6>
                <br>
                <h6 class="text-primary">IMPORTANT: For clients outside Philippines, please provide us a VALID email address for us to communicate with you. Upon submission, a link of our WhatsApp, Viber, Telegram, 
                    and Facebook Messenger account will be sent to the email you provide.</h6>
        </section>



</section>