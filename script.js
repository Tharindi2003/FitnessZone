// Select all navigation items
const navItems = document.querySelectorAll('nav ul li');

// Add hover event listeners
navItems.forEach(item => {
  item.addEventListener('mouseover', () => {
    // Highlight the item with an orange box
    item.style.backgroundColor = 'orangered';
    item.style.color = 'white';
    item.style.borderRadius = '5px';
  });

  item.addEventListener('mouseout', () => {
    // Remove the highlight
    item.style.backgroundColor = '';
    item.style.color = '#333';
    item.style.borderRadius = '';
  });
});

// Slideshow data
const slides = [
    {
      image: "image/slide1.jpg",
      title: "Information about fitness programs, trainers, and facilities.",
      description: "Want to get in shape by this summer? Try our best packages according to your schedule.",
    },
    {
      image: "image/slide2.jpg",
      title: "Achieve your dream body with our expert trainers.",
      description: "Join our personalized training programs for fast results.",
    },
    {
      image: "image/slide3.jpg",
      title: "State-of-the-art fitness equipment for every goal.",
      description: "Our facilities are designed to meet every fitness need.",
    },
    {
      image: "image/slide4.jpg",
      title: "Success doesn’t come from what you do occasionally, it comes from what you do consistently.",
      description: "We can turn your motivation in to mucels.",
    },
  ];
  
  // HTML elements
  const slideImage = document.getElementById("slide-image");
  const slideTitle = document.getElementById("slide-title");
  const slideDescription = document.getElementById("slide-description");
  
  // Current slide index
  let currentSlide = 0;
  
  // Function to display the current slide
  function displaySlide(index) {
    const slide = slides[index];
    console.log("Loading slide:", slide);
    slideImage.src = slide.image;
    slideTitle.textContent = slide.title;
    slideDescription.textContent = slide.description;
  }
  
  // Function to move to the next slide
  function nextSlide() {
    currentSlide = (currentSlide + 1) % slides.length;
    displaySlide(currentSlide);
  }
  
  // Function to move to the previous slide
  function prevSlide() {
    currentSlide = (currentSlide - 1 + slides.length) % slides.length; // Handles cycling backward
    displaySlide(currentSlide);
  }
  
  // Automatically change the slide every 5 seconds
  setInterval(nextSlide, 5000);
  
  // Display the initial slide
  displaySlide(currentSlide);
  
  document.getElementById("explore-more-btn").addEventListener("click", function () {
    fetch("index.php?action=fetch_more_coaches")
      .then((response) => response.json())
      .then((data) => {
        const moreCoachesSection = document.getElementById("more-coaches-list");
  
        if (data.length === 0) {
          moreCoachesSection.innerHTML = "<p>No more coaches available.</p>";
          return;
        }
  
        data.forEach((coach) => {
          const coachItem = document.createElement("div");
          coachItem.className = "coach-item";
  
          coachItem.innerHTML = `
            <h3>${coach.c_firstname} ${coach.c_lastname}</h3>
            <p>Email: ${coach.c_email}</p>
            <p>Gender: ${coach.c_gender}</p>
          `;
  
          moreCoachesSection.appendChild(coachItem);
        });
      })
      .catch((error) => console.error("Error fetching coaches:", error));
  });
  

  