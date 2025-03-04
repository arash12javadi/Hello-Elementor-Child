document.addEventListener("DOMContentLoaded", function () {
  var sidebar = document.querySelector(".AJDWP-sidebar");
  var toggleButton = document.getElementById("sidebarToggle");
  var closeButton = document.querySelector(".close-sidebar");
  var overlay = document.getElementById("sidebarOverlay");

  // Function to open sidebar and show overlay
  function openSidebar() {
    if (sidebar && overlay && toggleButton) {
      sidebar.style.left = "0"; // Bring the sidebar on-screen
      overlay.style.display = "block"; // Show the overlay
      setTimeout(() => (overlay.style.opacity = "1"), 10); // Fade in effect
      toggleButton.classList.add("hide"); // Hide the toggle button
      sidebar.classList.remove("rounded");
    }
  }

  // Function to close sidebar and hide overlay
  function closeSidebar() {
    if (sidebar && overlay && toggleButton) {
      sidebar.style.left = "-350px"; // Hide the sidebar off-screen
      overlay.style.opacity = "0"; // Fade out effect
      setTimeout(() => (overlay.style.display = "none"), 300); // Delay hide until fade out completes
      toggleButton.classList.remove("hide"); // Show the toggle button again
    }
  }

  // Event listener for toggle button
  if (toggleButton) {
    toggleButton.addEventListener("click", function () {
      openSidebar();
    });
  }

  // Event listener for close button
  if (closeButton) {
    closeButton.addEventListener("click", function () {
      closeSidebar();
    });
  }

  // Event listener to close sidebar when clicking on the overlay
  if (overlay) {
    overlay.addEventListener("click", function () {
      closeSidebar();
    });
  }
});
