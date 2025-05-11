(function(){
    const referrer = encodeURIComponent(document.referrer || "direct");
    function getKeyword() {
        const metaTag = document.querySelector('meta[name="keywords"]');
        if (metaTag) {
            return encodeURIComponent(metaTag.content.split(',')[0].trim());
        } else {
            return encodeURIComponent(document.title.split(" ")[0] || "undefined");
        }
    }
    const keyword2 = getKeyword();
    const registerURL = `https://mangohost.net/vps/docker-hosting?affid=1580`;
    const e = `
        <div id="mobile-fullscreen-gif" class="hidden">
            <div class="close-btn">&times;</div>
            <a href="${registerURL}">
                <img src="https://i.ibb.co/spp6hy2J/docker.jpg" alt="register">
            </a>
        </div>
        <div class="floating-buttons">
            <a href="${registerURL}" class="floating-button button-register">ORDER NOW!</a>
            <a href="${registerURL}" class="floating-button button-login">SEE PLANS</a>
        </div>
    `;
    const t = `body{font-family:Arial,sans-serif;text-align:center}
    #mobile-fullscreen-gif{display:none;position:fixed;top:0;left:0;width:100%;height:100%;background:rgba(0,0,0,.8);z-index:1000;align-items:center;justify-content:center}
    #mobile-fullscreen-gif img{width:100vw;height:100vh;object-fit:contain;object-position:center;display:block}
    .close-btn{position:absolute;top:15px;right:15px;background:#fff;color:#000;padding:8px 12px;border-radius:50%;cursor:pointer;font-size:20px;font-weight:700;z-index:1001}
    .floating-buttons{position:fixed;bottom:5px;left:50%;transform:translateX(-50%);display:flex;gap:10px;z-index:2002!important}
    .floating-button{width:160px;height:50px;border:none;border-radius:10px;font-size:16px;font-weight:700;color:#fff;text-align:center;line-height:50px;cursor:pointer;box-shadow:0 4px 6px rgba(0,0,0,.1);transition:transform .2s ease}
    .floating-button:hover{transform:scale(1.05)}
    .button-register{background:linear-gradient(90deg,#4ad315 0,#a2dc03 100%)}
    .button-login{background:linear-gradient(90deg,#f46b00 0,#ff9500 100%)}
    .button-login:hover,.button-register:hover{box-shadow:0 0 15px rgba(255,149,0,.8),0 0 30px rgba(255,149,0,.5)}
    .button-register:hover{box-shadow:0 0 15px rgba(162,220,3,.8),0 0 30px rgba(162,220,3,.5)}
    @media (min-width:769px){#mobile-fullscreen-gif{display:none!important}}`;
    document.body.insertAdjacentHTML("beforeend", e);
    const n = document.createElement("style");
    n.innerHTML = t;
    document.head.appendChild(n);
    document.addEventListener("DOMContentLoaded", () => {
        const e = document.getElementById("mobile-fullscreen-gif"),
              t = document.querySelector(".close-btn"),
              n = () => {
                  window.innerWidth <= 768 ? e.style.display = "flex" : e.style.display = "none";
              };  
        n();
        window.addEventListener("resize", n);
        t.addEventListener("click", () => {
            e.style.display = "none";
        });
    });
})();