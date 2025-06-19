# 💊 PD-lalas — The Pharmacy System with a Pulse!

Welcome to **PD-lalas** — a modern, web-based pharmacy sales & inventory system powered by **Laravel 10** and a touch of caffeine ☕. It’s got everything you need to track meds, log sales, and keep your stockroom as clean as your codebase.

🔗 **Live Demo:** [pd-lalas-production.up.railway.app 🚀](https://pd-lalas-production.up.railway.app)

---

## ✨ Why You’ll Love It

- 🔐 **OTP login + Admin approval** — because security is a prescription we always fill  
- 💊 **Manage medicines & supplies** with real-time stock tracking  
- 🧾 **Log sales with discounts** (hello Senior Citizens & PWDs!)  
- 📊 **Dashboard charts that slap** (thanks, Chart.js)  
- 🧠 **Smart role-based access** — admins, you're in charge  
- ☁️ **1-click Railway deploy** — push code, sip coffee

---

## 🛠️ Under the Hood (Tech Stack)

| Layer      | Toolset                     |
|------------|-----------------------------|
| 🧠 Backend  | Laravel 10                  |
| 🎨 Frontend | Blade + Tailwind (via Vite) |
| 🧪 DB       | MySQL (Railway-hosted)      |
| 🔐 Auth     | OTP + Admin Approval        |
| 🚂 Deploy   | Railway (Nixpacks)          |
| 📊 Charts   | Chart.js + AJAX             |
| 🔌 Extras   | jQuery, FontAwesome         |

---

## 📂 Key Files & Where to Find Stuff

| Path                                             | Purpose                      |
|--------------------------------------------------|------------------------------|
| `routes/web.php`                                 | App routes                   |
| `resources/views/`                               | Blade views/templates        |
| `database/seeders/AdminUserSeeder.php`           | Default admin accounts       |
| `app/Http/Controllers/SaleController.php`        | Sales logic                  |
| `vite.config.mjs`                                | Vite asset bundler config    |
| `resources/js/app.js`                            | Main JS w/ interactivity     |

---

## 🧪 Known Quirks & Quick Fixes

- ❗ **Mixed Content Errors?** — Make sure **every request uses HTTPS**, even in JS
- 📉 **Chart not showing?** — Confirm Chart.js is imported & `Chart` is globally accessible
- 🐢 **Slow builds?** — Railway’s free plan is chill like that. Be patient 😎
- 🧾 **Can't log sales?** — Check your browser console and make sure the `/sales/store` route is secure

---


---

## 📜 License

This project is licensed under **MIT** — use it, fork it, improve it, remix it. Just don’t sell meds with bugs. 💊🐞

---

Made with ❤️ by [@hxt-iiixi](https://github.com/hxt-iiixi)

---

> ⚡ Tip: Want screenshot embeds, status badges, or a one-click deploy button? Ping me.
