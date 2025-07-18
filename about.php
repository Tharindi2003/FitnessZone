<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>About Us - Fitness Zone</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        html {
            scroll-behavior: smooth;
        }

        body {
            font-family: 'Arial', sans-serif;
            background: linear-gradient(135deg, #0a0a0a 0%, #1a1a1a 100%);
            color: #ffffff;
            line-height: 1.6;
            overflow-x: hidden;
        }

        /* Header Styles */
        header {
            background: rgba(0, 0, 0, 0.9);
            backdrop-filter: blur(10px);
            position: fixed;
            width: 100%;
            top: 0;
            z-index: 1000;
            box-shadow: 0 2px 20px rgba(255, 69, 0, 0.3);
        }

        .header-container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 1rem 2rem;
        }

        .logo {
            height: 60px;
            transition: transform 0.3s ease;
        }

        .logo:hover {
            transform: scale(1.1);
        }

        /* Main Content Spacing */
        main {
            margin-top: 80px;
        }

        /* About Container */
        .about-container {
            text-align: center;
            padding: 80px 20px;
            background: linear-gradient(135deg, rgba(255, 69, 0, 0.1) 0%, rgba(255, 140, 0, 0.05) 100%);
            position: relative;
            overflow: hidden;
        }

        .about-container::before {
            content: '';
            position: absolute;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: radial-gradient(circle, rgba(255, 69, 0, 0.1) 0%, transparent 70%);
            animation: pulse 4s ease-in-out infinite;
        }

        @keyframes pulse {
            0%, 100% { transform: scale(1); opacity: 0.5; }
            50% { transform: scale(1.1); opacity: 0.8; }
        }

        .about-container h1 {
            font-size: 3.5rem;
            background: linear-gradient(45deg, #ff4500, #ff8c00);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            margin-bottom: 30px;
            position: relative;
            z-index: 2;
            animation: slideInDown 1s ease-out;
        }

        @keyframes slideInDown {
            from { opacity: 0; transform: translateY(-50px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .about-container p {
            font-size: 1.3rem;
            color: #e0e0e0;
            line-height: 1.8;
            margin: 20px auto;
            max-width: 900px;
            position: relative;
            z-index: 2;
            animation: fadeInUp 1s ease-out 0.3s both;
        }

        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(30px); }
            to { opacity: 1; transform: translateY(0); }
        }

        /* Achievements Section */
        .achievements-container {
            margin: 60px 0;
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 30px;
            max-width: 1200px;
            margin: 60px auto;
            padding: 0 20px;
        }

        .achievement {
            background: linear-gradient(135deg, rgba(255, 255, 255, 0.1) 0%, rgba(255, 255, 255, 0.05) 100%);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 69, 0, 0.3);
            padding: 40px 20px;
            border-radius: 20px;
            text-align: center;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }

        .achievement::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 69, 0, 0.1), transparent);
            transition: left 0.5s ease;
        }

        .achievement:hover::before {
            left: 100%;
        }

        .achievement:hover {
            transform: translateY(-10px);
            box-shadow: 0 20px 40px rgba(255, 69, 0, 0.3);
            border-color: #ff4500;
        }

        .achievement h2 {
            font-size: 3rem;
            color: #ff4500;
            margin-bottom: 10px;
            font-weight: bold;
        }

        .achievement p {
            font-size: 1.1rem;
            color: #cccccc;
            font-weight: 500;
            text-align : center;
        }

        /* Section Styles */
        section {
            margin-bottom: 40px;
            padding: 40px 20px;
            max-width: 1200px;
            margin: 0 auto 40px;
        }

        section h2 {
            color: #ff4500;
            font-size: 2.5rem;
            margin-bottom: 20px;
            text-align: center;
            position: relative;
            padding-bottom: 15px;
        }

        section h2::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 50%;
            transform: translateX(-50%);
            width: 80px;
            height: 3px;
            background: linear-gradient(90deg, #ff4500, #ff8c00);
            border-radius: 2px;
        }

        section p {
            font-size: 1.1rem;
            line-height: 1.8;
            color: #e0e0e0;
            text-align: justify;
        }

        /* Equipment Section */
        .equipments-container {
            padding: 80px 20px;
            background: linear-gradient(135deg, rgba(255, 69, 0, 0.05) 0%, rgba(0, 0, 0, 0.8) 100%);
            margin: 60px 0;
            max-width: 1800px;
        }

        .equipments-container h2 {
            font-size: 3rem;
            color: #ff4500;
            margin-bottom: 50px;
            text-align: center;
        }

        .equipment-list {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 30px;
            margin: 0 auto;
        }

        .equipment {
            background: linear-gradient(135deg, rgba(255, 255, 255, 0.1) 0%, rgba(255, 255, 255, 0.05) 100%);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 69, 0, 0.2);
            padding: 30px;
            border-radius: 20px;
            text-align: center;
            transition: all 0.3s ease;
            overflow: hidden;
            position: relative;
        }

        .equipment::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: linear-gradient(135deg, rgba(255, 69, 0, 0.1), transparent);
            opacity: 0;
            transition: opacity 0.3s ease;
        }

        .equipment:hover::before {
            opacity: 1;
        }

        .equipment:hover {
            transform: translateY(-10px);
            box-shadow: 0 20px 40px rgba(255, 69, 0, 0.3);
            border-color: #ff4500;
        }

        .equipment h3 {
            font-size: 1.8rem;
            color: #ff4500;
            margin-bottom: 20px;
        }

        .equipment img {
            width: 100%;
            height: 200px;
            object-fit: cover;
            border-radius: 15px;
            margin-bottom: 20px;
            transition: transform 0.3s ease;
        }

        .equipment:hover img {
            transform: scale(1.05);
        }

        .equipment-p-color {
            color: #e0e0e0;
            font-size: 1.1rem;
            line-height: 1.6;
        }

        /* Reviews Section */
        .customer-reviews-section {
            padding: 80px 20px;
            background: linear-gradient(135deg, rgba(255, 140, 0, 0.05) 0%, rgba(0, 0, 0, 0.9) 100%);
            text-align: center;
        }

        .customer-reviews-section h2 {
            font-size: 3rem;
            color: #ff4500;
            margin-bottom: 50px;
        }

        .reviews-carousel {
            display: flex;
            overflow: hidden;
            position: relative;
            width: 100%;
            max-width: 800px;
            margin: 0 auto;
            border-radius: 20px;
        }

        .review-item {
            flex: 0 0 100%;
            animation: moveReviews 20s linear infinite;
            padding: 40px;
            background: linear-gradient(135deg, rgba(0, 0, 0, 0.95) 0%, rgba(252, 118, 0, 0.9) 100%);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 69, 0, 0.2);
            border-radius: 20px;
            margin: 10px;
            color: #333;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
        }

        .review-item p {
            font-size: 1.2rem;
            font-style: italic;
            margin-bottom: 20px;
            line-height: 1.6;
        }

        .review-item h4 {
            color: #ff4500;
            font-size: 1.1rem;
            font-weight: bold;
        }

        @keyframes moveReviews {
            0% { transform: translateX(0); }
            25% { transform: translateX(-100%); }
            50% { transform: translateX(-200%); }
            75% { transform: translateX(-300%); }
            100% { transform: translateX(0); }
        }

        /* Review Form */
        .submit-review-section {
            padding: 60px 20px;
            background: linear-gradient(135deg, rgba(255, 69, 0, 0.1) 0%, rgba(0, 0, 0, 0.9) 100%);
            text-align: center;
        }

        .submit-review-section h2 {
            font-size: 2.5rem;
            color: #ff4500;
            margin-bottom: 40px;
        }

        .review-form {
            display: flex;
            flex-direction: column;
            gap: 20px;
            max-width: 500px;
            margin: 0 auto;
        }

        .review-form input,
        .review-form textarea {
            padding: 15px 20px;
            border: 2px solid rgba(255, 69, 0, 0.3);
            border-radius: 10px;
            background: rgba(255, 255, 255, 0.1);
            color: #ffffff;
            font-size: 1rem;
            transition: all 0.3s ease;
        }

        .review-form input:focus,
        .review-form textarea:focus {
            outline: none;
            border-color: #ff4500;
            box-shadow: 0 0 20px rgba(255, 69, 0, 0.3);
            background: rgba(255, 255, 255, 0.15);
        }

        .review-form input::placeholder,
        .review-form textarea::placeholder {
            color: #cccccc;
        }

        .star-rating {
            display: flex;
            justify-content: center;
            gap: 10px;
            margin: 20px 0;
        }

        .star {
            font-size: 2rem;
            color: #666;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .star:hover,
        .star.selected {
            color: #ff4500;
            transform: scale(1.2);
        }

        .review-form button {
            padding: 15px 30px;
            background: linear-gradient(135deg, #ff4500, #ff8c00);
            color: white;
            border: none;
            border-radius: 10px;
            font-size: 1.1rem;
            font-weight: bold;
            cursor: pointer;
            transition: all 0.3s ease;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .review-form button:hover {
            background: linear-gradient(135deg, #ff6a00, #ffa500);
            transform: translateY(-3px);
            box-shadow: 0 10px 20px rgba(255, 69, 0, 0.4);
        }

        /* Footer */
        footer1 {
            background: linear-gradient(135deg, #0a0a0a 0%, #1a1a1a 100%);
            border-top: 1px solid rgba(255, 69, 0, 0.3);
            padding: 40px 20px;
            text-align: center;
        }

        footer1 p {
            color: #888;
            font-size: 1rem;
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .about-container h1 {
                font-size: 2.5rem;
            }

            .about-container p {
                font-size: 1.1rem;
            }

            .achievements-container {
                grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
                gap: 20px;
            }

            .achievement h2 {
                font-size: 2.5rem;
            }

            section h2 {
                font-size: 2rem;
            }

            .equipments-container h2 {
                font-size: 2.5rem;
            }

            .equipment-list {
                grid-template-columns: 1fr;
            }
        }

        /* Scroll animations */
        @keyframes slideInFromLeft {
            from { opacity: 0; transform: translateX(-50px); }
            to { opacity: 1; transform: translateX(0); }
        }

        @keyframes slideInFromRight {
            from { opacity: 0; transform: translateX(50px); }
            to { opacity: 1; transform: translateX(0); }
        }

        .animate-left {
            animation: slideInFromLeft 0.8s ease-out;
        }

        .animate-right {
            animation: slideInFromRight 0.8s ease-out;
        }
    </style>
</head>

<body>
    <header>
        <div class="header-container">
            <div class="header-left">
                <img src="logo/logo.png" alt="Fitness Zone Logo" class="logo">
            </div>
        </div>
    </header>

    <main>
        <div class="about-container">
            <h1>About Fitness Zone</h1>
            <p>Welcome to <strong>Fitness Zone</strong>, the ultimate destination for fitness enthusiasts since <strong>2010</strong>.
                With a legacy spanning over a decade, we've helped thousands achieve their fitness goals and live healthier, happier lives.
                We pride ourselves on offering world-class facilities, cutting-edge equipment, and personalized training programs tailored to every individual.</p>
        </div>

        <section class="achievements-container">
            <div class="achievement">
                <h2>10,000+</h2>
                <p>Happy Customers</p>
            </div>
            <div class="achievement">
                <h2>14</h2>
                <p>Years of Excellence</p>
            </div>
            <div class="achievement">
                <h2>50+</h2>
                <p>Professional Trainers</p>
            </div>
            <div class="achievement">
                <h2>20+</h2>
                <p>Fitness Awards Won</p>
            </div>
        </section>

        <section class="customer-reviews-section">
            <h2>What Our Customers Say</h2>
            <div class="reviews-carousel">
                <div class="review-item">
                    <p>"The best gym experience I've ever had! Highly recommend!"</p>
                    <h4>- John Doe</h4>
                </div>
                <div class="review-item">
                    <p>"Amazing trainers and top-notch facilities. Love it!"</p>
                    <h4>- Jane Smith</h4>
                </div>
                <div class="review-item">
                    <p>"Great environment and community. My fitness journey has been transformed!"</p>
                    <h4>- Mike Johnson</h4>
                </div>
                <div class="review-item">
                    <p>"Affordable pricing and excellent support. Five stars!"</p>
                    <h4>- Emily Davis</h4>
                </div>
            </div>
        </section>

        <section class="submit-review-section">
            <h2>Write a Review</h2>
            <form class="review-form">
                <input type="text" name="name" placeholder="Your Name" required />
                <textarea name="review" rows="4" placeholder="Your Review" required></textarea>
                <div class="star-rating">
                    <span class="star" data-value="1">&#9734;</span>
                    <span class="star" data-value="2">&#9734;</span>
                    <span class="star" data-value="3">&#9734;</span>
                    <span class="star" data-value="4">&#9734;</span>
                    <span class="star" data-value="5">&#9734;</span>
                </div>
                <input type="hidden" name="rating" id="rating-input" />
                <button type="submit">Submit Review</button>
            </form>
        </section>

        <section class="equipments-container">
            <h2>State-of-the-Art Equipment</h2>
            <div class="equipment-list">
                <div class="equipment">
                    <h3>Cardio Machines</h3>
                    <img src="image/weight.jpg" alt="Cardio Machines">
                    <p class="equipment-p-color">Top-notch treadmills, ellipticals, and bikes to elevate your heart rate and boost endurance.</p>
                </div>
                <div class="equipment">
                    <h3>Weightlifting</h3>
                    <img src="image/wl.jpg" alt="Weightlifting Equipment">
                    <p class="equipment-p-color">Dumbbells, barbells, and resistance machines for strength training.</p>
                </div>
                <div class="equipment">
                    <h3>Yoga Studio</h3>
                    <img src="image/yoga.jpg" alt="Yoga Studio">
                    <p class="equipment-p-color">A serene space for yoga, pilates, and stretching exercises.</p>
                </div>
                <div class="equipment">
                    <h3>Functional Training</h3>
                    <img src="image/ft.jpg" alt="Functional Training">
                    <p class="equipment-p-color">TRX systems, kettlebells, and ropes to add versatility to your workout.</p>
                </div>
            </div>
        </section>

        <section id="gym" class="animate-left">
            <h2>GYM</h2>
            <p>Our gym is designed to cater to all fitness levels, providing state-of-the-art machines, free weights, and functional training equipment. Members can take advantage of personalized workout plans and group sessions guided by certified trainers, ensuring a safe and effective fitness journey in a supportive environment.</p>
        </section>

        <section id="zumba" class="animate-right">
            <h2>Zumba</h2>
            <p>Zumba is a fun and energetic dance workout that blends Latin and international music with cardio-based moves. Our certified instructors lead sessions tailored for all fitness levels, helping members burn calories and improve endurance while enjoying an engaging, party-like atmosphere.</p>
        </section>

        <section id="personal-training" class="animate-left">
            <h2>Personal Training</h2>
            <p>Personal training offers a customized fitness experience, where members work one-on-one with certified professionals to meet their unique goals. Our trainers create tailored workout routines, provide nutrition advice, and offer motivation to ensure maximum progress in a focused setting.</p>
        </section>

        <section id="group-class" class="animate-right">
            <h2>Group Class</h2>
            <p>Our group classes include a variety of options like yoga, HIIT, spinning, and more. These sessions are designed to keep members motivated by blending high-energy workouts with the camaraderie of group participation, all under the guidance of experienced instructors.</p>
        </section>

        <section id="healthy-cafe" class="animate-left">
            <h2>Healthy Cafe</h2>
            <p>Our Healthy Cafe serves as the perfect spot for post-workout recovery or a quick, nutritious snack. With a range of protein shakes, healthy snacks, and balanced meal options, the cafe complements your fitness goals by offering fuel for energy and recovery.</p>
        </section>

        <section id="meal-plan" class="animate-right">
            <h2>Meal Plan</h2>
            <p>Tailored meal plans are a cornerstone of holistic fitness. Our dietitians craft personalized nutrition programs to match each member's goals, whether it's weight loss, muscle gain, or maintenance. These plans are designed to integrate seamlessly into a healthy and active lifestyle.</p>
        </section>

        <section id="workout-plan" class="animate-left">
            <h2>Workout Plan</h2>
            <p>Structured workout plans are created based on individual fitness assessments to help members achieve their goals. These routines provide a clear path for progress, incorporating strength, endurance, and flexibility exercises under the guidance of expert trainers.</p>
        </section>

        <section id="cardio" class="animate-right">
            <h2>Cardio</h2>
            <p>Cardio training focuses on improving heart health and burning calories through exercises like running, cycling, or rowing. With access to a variety of equipment, our members can enjoy effective, high-energy workouts to boost stamina and overall fitness.</p>
        </section>
    </main>

    <footer1>
        <p>© 2024 Fitness Zone. All Rights Reserved.</p>
    </footer1>

    <script>
        // Star Rating Script
        document.addEventListener("DOMContentLoaded", () => {
            const stars = document.querySelectorAll(".star");
            const ratingInput = document.getElementById("rating-input");

            stars.forEach((star, index) => {
                star.addEventListener("click", () => {
                    stars.forEach((s) => s.classList.remove("selected"));
                    
                    const value = star.getAttribute("data-value");
                    for (let i = 0; i < value; i++) {
                        stars[i].classList.add("selected");
                    }
                    ratingInput.value = value;
                });

                star.addEventListener("mouseenter", () => {
                    const value = star.getAttribute("data-value");
                    for (let i = 0; i < value; i++) {
                        stars[i].style.color = "#ff4500";
                    }
                });

                star.addEventListener("mouseleave", () => {
                    stars.forEach((s) => {
                        if (!s.classList.contains("selected")) {
                            s.style.color = "#666";
                        }
                    });
                });
            });

            // Scroll animations
            const observerOptions = {
                threshold: 0.1,
                rootMargin: '0px 0px -50px 0px'
            };

            const observer = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        entry.target.style.opacity = '1';
                        entry.target.style.transform = 'translateY(0)';
                    }
                });
            }, observerOptions);

            // Observe all sections
            document.querySelectorAll('section').forEach(section => {
                section.style.opacity = '0';
                section.style.transform = 'translateY(30px)';
                section.style.transition = 'opacity 0.6s ease, transform 0.6s ease';
                observer.observe(section);
            });
        });
    </script>
</body>

</html>