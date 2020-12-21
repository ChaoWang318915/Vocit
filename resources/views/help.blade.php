@extends('layouts.app')
@section('content')
    <div class="ui container">
        <div class="ui grid">
            <div class="column">
                <div class="ui large header primary-header">FAQs</div>               
            </div>
        </div>
        <div class="ui grid">
            <div class="column">
                <div class="ui fluid accordion">
                    <h3>GENERAL FAQS</h3>
                    <div class="title active">
                        <i class="dropdown icon"></i>
                        What information do you need to register my account?
                    </div>
                    <div class="content active">
                        <p class="transition visible">We will require you to provide your name, your email address, and under certain circumstances, telephone number in order to register your Vocit account. In the case of a business account, we may also ask you to provide us with your business registration information.</p>
                    </div>

                    <div class="title">
                        <i class="dropdown icon"></i>
                        Can any user from any country register a Vocit account?
                    </div>
                    <div class="content">
                        <p class="transition hidden">Yes. Even though we are domiciled in the United States, users from around the world are welcome to use our service, provided that they do so in full compliance with all pertinent country and jurisdictional laws.</p>
                    </div>


                    <div class="title">
                        <i class="dropdown icon"></i>
                        Do you store personal information?
                    </div>
                    <div class="content">
                        <p class="transition hidden">Yes, we do. Please read out privacy policy to understand what personal information we collect from you.</p>
                    </div>


                    <div class="title">
                        <i class="dropdown icon"></i>
                        What about exchanged pictures?
                    </div>
                    <div class="content">
                        <p class="transition hidden">We save all exchanged pictures on the platform in our database.</p>
                    </div>


                    <h3>FAQS FOR USERS</h3>

                    <div class="title">
                        <i class="dropdown icon"></i>
                        Is a user account free to register? 
                    </div>
                    <div class="content">
                        <p class="transition hidden">Yes, you can sign up for a user account and start earning rewards and discounts for your pictures <here>. </p>
                    </div>

                    <div class="title">
                        <i class="dropdown icon"></i>
                        For what purpose can businesses use my provided pictures? 
                    </div>
                    <div class="content">
                        <p class="transition hidden">Businesses are under an obligation to only use any picture you make available to them for their business marketing operations only. They are under strict instructions not to resell the picture, and they are not allowed to use the picture with any intention to embarrass or harass the people, animals, or objects in the picture.</p>
                    </div>

                    <div class="title">
                        <i class="dropdown icon"></i>
                        Does registering an independent user account make me an employee of Vocit?
                    </div>
                    <div class="content">
                        <p class="transition hidden">No. The terms of our agreement make no provisions for full time employment. You shall remain an independent user and not an employee of Vocit.</p>
                    </div>

                    <div class="title">
                        <i class="dropdown icon"></i>
                        Can I claim any other compensation apart from the reward promised by the business I provide user generated content to?
                    </div>
                    <div class="content">
                        <p class="transition visible">No. Vocit does not reward users with any type of compensation, besides the agreed upon payment with the corresponding business who requests for the user generated content.</p>
                    </div>
                    
                    <div class="title">
                        <i class="dropdown icon"></i>
                        Can I provide businesses with pictures that are not mine?
                    </div>
                    <div class="content">
                        <p class="transition hidden">No. You must have all the rights and approvals to provide the user content to any business.</p>
                    </div>

                    <div class="title">
                        <i class="dropdown icon"></i>
                        What if the business does not fulfil its reward for my user content?
                    </div>
                    <div class="content">
                        <p class="transition hidden">We are not guaranteeing that any business has the ability to fulfil any promised reward. You, and not Vocit, has the responsibility of asking for and receiving your reward from any business.</p>
                    </div>




                    <h3>FAQS FOR BUSINESSES</h3>
                    <div class="title">
                        <i class="dropdown icon"></i>
                        Is a business account free to register? 
                    </div>
                    <div class="content">
                        <p class="transition visible">Yes, you can sign up for a free business account <a href="{{url('register')}}">here</a> to start engaging customers for pictures to use for business marketing purposes. However, the free business account is for trial purposes only and has limitations.</p>
                    </div>

                    <div class="title">
                        <i class="dropdown icon"></i>
                        How can I enjoy all the features of a business account?
                    </div>
                    <div class="content">
                        <p class="transition visible">If you are enjoying your trial run of your business account and would love to unlock all the features, you will be charged for a monthly subscription or a onetime payment for your use of the website.</p>
                    </div>

                    <div class="title">
                        <i class="dropdown icon"></i>
                        What if I decide to cancel my account? Will I get a refund?
                    </div>
                    <div class="content">
                        <p class="transition visible">Unfortunately, we do not offer a refund. It is our sincere hope that you love our services so much that you continue to use it. If you, however, are no longer interested in our services, you can cancel your subscription.</p>
                    </div>


                    <div class="title">
                        <i class="dropdown icon"></i>
                        How am I permitted to use the user generated content that I receive from independent users?
                    </div>
                    <div class="content">
                        <p class="transition visible">You are only allowed to use any received picture for your business marketing operations only. You are not allowed to resell the picture, and may not use such pictures with any intention to embarrass or harass the people, animals or objects contained in it.</p>
                    </div>

                    
                    <div class="title">
                        <i class="dropdown icon"></i>
                        What if there is any dispute between me and the user?
                    </div>
                    <div class="content">
                        <p class="transition visible">We encourage all businesses and independent users to resolve disputes amongst themselves. Where we choose to intervene, you accept that we will never be liable for the outcome of any such disputes.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
