<?php 
include ('logic/index_logic.php'); 
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome to LokiMart</title>
    <link href="bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/bootstrap-icons.css">
    <link rel="stylesheet" href="styles/style.css"> 
</head>
<body>
    <?php
    $pageTitle = 'Home'; 
    include ('includes/header.php') 
    ?>
    <main>
      <h2>Welcome, <?php echo htmlspecialchars($_SESSION['username']); ?>!</h2>
        <section class="product-details">
            <div class="product-info">
                <h2>About LokiMart</h2>
                <p>
                    Welcome to LokiMart – your trusted online marketplace. Built with performance and security in mind, LokiMart allows you to shop confidently and easily.
                </p>
                <p>
                    <p>
                    LokiMart is a proudly local digital venture, born out of a clear need for more accessible and modern e-commerce solutions in Lusaka. After recognising the limited number of reliable online shopping platforms in Lusaka, Zambia, we launched LokiMart to make shopping more convenient, allowing people to browse and purchase products from the comfort of their own homes—on their own time and terms.
                </p>

                <h2>Find us on the map</h2>
                <div class="iframe">
                  <div class="iframe-2">
                    <iframe
                        src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3846.2101747157035!2d28.285528110979115!3d-15.419203613767259!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x1940f350abae6451%3A0x95dacb9f087c1fe7!2sZambia%20Centre%20for%20Accountancy%20Studies%20(ZCAS)!5e0!3m2!1sen!2szm!4v1752090011381!5m2!1sen!2szm"
                        width="100%"
                        height="100%"
                        allowfullscreen=""
                        loading="lazy"
                        referrerpolicy="no-referrer-when-downgrade">
                    </iframe>
                  </div>
                </div>

                </p>
                <p>
                    Today, LokiMart reflects our evolution into digital retailing, bringing trusted products to customers with efficiency, transparency, and a commitment to quality. As we advance into the future, our focus remains on delivering exceptional value while upholding our social responsibility and ethical standards.
                </p>

                <h2>Our Mission</h2>
                <p>
                    At LokiMart, our mission is to foster sustainable digital commerce by offering a seamless, secure, and customer-centric shopping experience. We are committed to providing value, supporting responsible business practices, and contributing meaningfully to the communities we serve—both locally and globally.
                </p>

                <h2>Our Vision</h2>
                <p>
                    Our vision at LokiMart is to become a leading force in redefining online retail across emerging markets. We aim to blend innovation, sustainability, and integrity into every aspect of our platform. Through strategic partnerships and forward-thinking solutions, we strive to make a positive and lasting impact on consumers, industries, and the environment.
                </p>


                <h2>Why shop with us?</h2>
                <ul>
                    <li>✔ A wide range of quality products</li>
                    <li>✔ Account-based shopping for better security and personalisation</li>
                    <li>✔ Fast and reliable order processing</li>
                    <li>✔ Friendly and helpful customer support</li>
                </ul>

                
                <br>
                <h2>Frequently Asked Questions (FAQs)</h2>
                <!-- <br> -->
                <div class="accordion" id="faqAccordion">
                  <div class="accordion-item">
                    <h2 class="accordion-header" id="faq1">
                      <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapse1" aria-expanded="true" aria-controls="collapse1">
                        Do I need an account to buy products?
                      </button>
                    </h2>
                    <div id="collapse1" class="accordion-collapse collapse show" aria-labelledby="faq1" data-bs-parent="#faqAccordion">
                      <div class="accordion-body">
                        Yes, creating an account ensures secure and easily reviewed transactions.
                      </div>
                    </div>
                  </div>

                  <div class="accordion-item">
                    <h2 class="accordion-header" id="faq2">
                      <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse2" aria-expanded="false" aria-controls="collapse2">
                        How can I track my order?
                      </button>
                    </h2>
                    <div id="collapse2" class="accordion-collapse collapse" aria-labelledby="faq2" data-bs-parent="#faqAccordion">
                      <div class="accordion-body">
                        Once logged in, go to the cart page and click on View My Orders button to check your order status.
                      </div>
                    </div>
                  </div>

                  <div class="accordion-item">
                    <h2 class="accordion-header" id="faq3">
                      <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse3" aria-expanded="false" aria-controls="collapse3">
                        Can I cancel an order?
                      </button>
                    </h2>
                    <div id="collapse3" class="accordion-collapse collapse" aria-labelledby="faq3" data-bs-parent="#faqAccordion">
                      <div class="accordion-body">
                        Yes, orders in ‘Processing’ status can be cancelled by going to the cart page, then clicking on the View My Orders button, which is where you can cancel an order.
                      </div>
                    </div>
                  </div>

                  <div class="accordion-item">
                    <h2 class="accordion-header" id="faq4">
                      <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse4" aria-expanded="false" aria-controls="collapse4">
                        What payment methods are accepted?
                      </button>
                    </h2>
                    <div id="collapse4" class="accordion-collapse collapse" aria-labelledby="faq4" data-bs-parent="#faqAccordion">
                      <div class="accordion-body">
                        Currently, only mobile money (Zamtel, MTN, Airtel) is accepted.
                      </div>
                    </div>
                  </div>

                  <div class="accordion-item">
                    <h2 class="accordion-header" id="faq5">
                      <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse5" aria-expanded="false" aria-controls="collapse5">
                        How do I contact customer support?
                      </button>
                    </h2>
                    <div id="collapse5" class="accordion-collapse collapse" aria-labelledby="faq5" data-bs-parent="#faqAccordion">
                      <div class="accordion-body">
                        Use the contact form above or email us at <a href="mailto:alokvermaknp157@gmail.com">alokvermaknp157@gmail.com</a>.
                      </div>
                    </div>
                  </div>
                </div><!-- Bootstrap JS (for accordion) -->
                <script src="bootstrap/js/bootstrap.bundle.min.js"></script>
            </div>
        </section>
    </main>
    <?php include ('includes/footer.php'); ?>
    <script src="scripts/script.js"></script>
</body>
</html>