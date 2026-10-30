# Quest 3 AR & Web Vision (Erasmus+ AI for VET)

Interaktivna aplikacija za prepoznavanje objekata u realnom vremenu uz pomoć veštačke inteligencije (**TensorFlow.js COCO-SSD**), Three.js i WebXR tehnologija, razvijena u okviru projekta **Erasmus+ AI for VET**.

- **Uživo:** [https://vr.aiforvet.eu](https://vr.aiforvet.eu)
- **AISim Lab:** [https://sim.aiforvet.eu](https://sim.aiforvet.eu)
- **GitHub:** [https://github.com/AIForVet/vr-aiforvet](https://github.com/AIForVet/vr-aiforvet)

---

## 🚀 Ključne funkcionalnosti

### 1. Meta Quest 3 WebXR AR mod (Passthrough)
- **WebXR `immersive-ar` sesija** sa `local-floor` prostorom.
- **Detekcija površina (Plane Detection)**: automatski prepoznaje pod, zidove i stolove.
- **Hit-test kursor**: prostorno pozicioniranje na stvarnim površinama u sobi.
- **AI 3D Markeri**: objekti prepoznati preko kamere projektuju se kao 3D lebdeće labele i 3D okviri u prostoru korisnika.
- **Interakcija kontrolerom**: klik okidača (trigger) postavlja 3D objekte (Torus Knot) na označenu površinu u stvarnom prostoru.

### 2. Desktop / Mobilna Web kamera simulacija
- Namenjeno korisnicima koji pristupaju sa računara, laptopa ili telefona bez VR/AR naočara.
- Automatska detekcija dostupnosti WebXR AR režima.
- Prikaz video strima uživo putem Web kamere (`getUserMedia`).
- **Real-time AI detekcija objekata**: TensorFlow.js COCO-SSD model prepoznaje 80 svakodnevnih kategorija (osoba, laptop, stolica, šolja, telefon...) uz prikaz verovatnoće i kolornih okvira.
- Klik na video postavlja interaktivne 3D objekte na Three.js sceni.

---

## 🛠 Tehnologije

- **Three.js** (r157) — 3D renderovanje i WebXR upravljanje
- **TensorFlow.js** (v4.15.0) — klijentsko izvršavanje neuronskih mreža u browseru
- **COCO-SSD** (v2.2.3) — model za detekciju objekata
- **WebXR Device API** — passthrough AR na Meta Quest uređajima

---

## 📦 Pokretanje i Deploy

Aplikacija se sastoji od statičkih fajlova i može se pokrenuti na bilo kom HTTPS web serveru (HTTPS je obavezan za WebXR i pristup kameri).

### Deploy webhook
Deploy se automatski vrši preko `deploy.php` webhooka pri svakom `push`-u na `main` granu na GitHub-u:
```
git push origin main  ──►  https://vr.aiforvet.eu/deploy.php
```

---

## 📄 Licenca

GNU General Public License v3.0 — pogledajte [LICENSE](LICENSE) fajl.
