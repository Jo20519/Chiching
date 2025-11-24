document.querySelector("#loginForm").addEventListener("submit", async (e) => {
    e.preventDefault();
  
    const email = document.querySelector("#email").value;
    const password = document.querySelector("#password").value;
  
    const response = await fetch("http://localhost:8000/api/login", {
      method: "POST",
      headers: {
        "Content-Type": "application/json",
      },
      body: JSON.stringify({ email, password }),
    });
  
    const data = await response.json();
    if (response.ok) {
      alert("Login successful!");
      console.log(data);
      // maybe redirect to dashboard.html
    } else {
      alert(data.message || "Login failed");
    }
  });
  