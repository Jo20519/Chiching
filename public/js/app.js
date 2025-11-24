const API_BASE_URL = "http://127.0.0.1:8000/api";

// ✅ Handle Registration
document.getElementById("registerForm")?.addEventListener("submit", async (e) => {
  e.preventDefault();

  const name = document.getElementById("name").value;
  const email = document.getElementById("regEmail").value;
  const password = document.getElementById("regPassword").value;

  const res = await fetch(`${API_BASE_URL}/register`, {
    method: "POST",
    headers: { "Content-Type": "application/json" },
    body: JSON.stringify({ name, email, password })
  });

  const data = await res.json();
  alert(res.ok ? "Registration successful!" : data.message || "Failed to register");
});

// ✅ Handle Login
document.getElementById("loginForm")?.addEventListener("submit", async (e) => {
  e.preventDefault();

  const email = document.getElementById("email").value;
  const password = document.getElementById("password").value;

  const res = await fetch(`${API_BASE_URL}/login`, {
    method: "POST",
    headers: { "Content-Type": "application/json" },
    body: JSON.stringify({ email, password })
  });

  const data = await res.json();
  if (res.ok && data.token) {
    localStorage.setItem("auth_token", data.token);
    alert("Login successful!");
    window.location.href = "/dashboard";
  } else {
    alert(data.message || "Invalid credentials");
  }
});
