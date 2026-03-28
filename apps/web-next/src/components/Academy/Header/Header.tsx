"use client";

import { FormEvent, useEffect, useState } from "react";
import styles from "./Header.module.css";
import { BrandLogoSvg } from "./BrandLogoSvg";

const heroTrustLine = [
  { value: "20", label: "лет опыта" },
  { value: "5000+", label: "учеников" },
  { value: "INT", label: "международные проекты" },
];

export function Header() {
  const [isModalOpen, setIsModalOpen] = useState(false);
  const [isSending, setIsSending] = useState(false);
  const [formStatus, setFormStatus] = useState<"idle" | "success" | "error">("idle");
  const [formMessage, setFormMessage] = useState("");

  useEffect(() => {
    function onKeyDown(event: KeyboardEvent) {
      if (event.key === "Escape") {
        setIsModalOpen(false);
      }
    }

    if (isModalOpen) {
      window.addEventListener("keydown", onKeyDown);
    }

    return () => {
      window.removeEventListener("keydown", onKeyDown);
    };
  }, [isModalOpen]);

  async function handleSubmit(event: FormEvent<HTMLFormElement>) {
    event.preventDefault();
    const form = event.currentTarget;
    const formData = new FormData(form);
    const name = String(formData.get("name") ?? "").trim();
    const phone = String(formData.get("phone") ?? "").trim();
    const message = String(formData.get("message") ?? "").trim();
    if (!name || !phone) {
      setFormStatus("error");
      setFormMessage("Пожалуйста, заполните имя и телефон.");
      return;
    }
    setIsSending(true);
    setFormStatus("idle");
    setFormMessage("");
    try {
      const response = await fetch("/api/telegram-lead", {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify({ name, phone, message }),
      });
      if (!response.ok) {
        throw new Error("Send failed");
      }
      setFormStatus("success");
      setFormMessage("Спасибо за обращение, мы свяжемся с вами в ближайшее время.");
      form.reset();
      setTimeout(() => {
        setIsModalOpen(false);
        setFormStatus("idle");
        setFormMessage("");
      }, 1200);
    } catch (e) {
      setFormStatus("error");
      setFormMessage("Анкета не отправлена. Попробуйте позже.");
    } finally {
      setIsSending(false);
    }
  }

  return (
    <div className={styles.siteShell}>
      <header className={styles.headerWrap}>
        <div className={styles.container}>
          <div className={styles.headerTop}>
            <div className={styles.banner}>
              <div className={styles.shine} />
              <div className={styles.bannerTop}>
                <div className={styles.brandCluster}>
                  <div className={styles.logoWrap}>
                    <BrandLogoSvg className={styles.logoSvg} />
                  </div>
                </div>
                <div className={styles.titleBlock}>
                  <p className={styles.brandName}>BONSHERY GROOM</p>
                  <p className={styles.brandSubtitle}>Академия груминга</p>
                </div>
              </div>
              <div className={styles.goldLine} />
              <div className={styles.bannerBottom}>
                <a className={styles.phone} href="tel:+79258899963">
                  +7 (925) 889-99-63
                </a>
              </div>
            </div>
          </div>
          <div className={styles.heroStage}>
            <div className={styles.heroText}>
              <p className={styles.heroEyebrow}>Международная академия груминга BONSHERY</p>
              <h1 className={styles.heroTitle}>
                Профессия грумера как система
                <br />
                <span className={styles.heroTitleAccent}>от первого навыка до собственного бизнеса</span>
              </h1>
              <p className={styles.heroDescription}>
                Мы не продаём лёгкую профессию за 7 дней и не обещаем быстрые деньги.
                Этот путь для тех, кто готов учиться, работать и выходить в профессию осознанно.
              </p>
              <div className={styles.heroActions}>
                <button
                  type="button"
                  className={styles.heroPrimary}
                  onClick={() => {
                    document.getElementById("quick-choice")?.scrollIntoView({ behavior: "smooth" });
                  }}
                >
                  Курс с нуля
                </button>
                <button
                  type="button"
                  className={styles.heroSecondary}
                  onClick={() => {
                    document.getElementById("growth")?.scrollIntoView({ behavior: "smooth" });
                  }}
                >
                  Мастер-классы
                </button>
                <button
                  type="button"
                  className={styles.heroSecondary}
                  onClick={() => {
                    document.getElementById("growth-program")?.scrollIntoView({ behavior: "smooth" });
                  }}
                >
                  Программа роста
                </button>
                <a
                  href="https://t.me/maxbonshery"
                  target="_blank"
                  rel="noopener noreferrer"
                  className={styles.heroGhost}
                >
                  Получить консультацию
                </a>
              </div>
              {/* Баннер с ближайшими датами курса */}
              <div className={styles.courseBanner}>
                <strong>Ближайшие даты курса:</strong> 5 апреля, 20 мая, 10 июня
              </div>
              <div className={styles.heroTrustRow}>
                {heroTrustLine.map((item) => (
                  <div key={item.label} className={styles.heroTrustItem}>
                    <span className={styles.heroTrustValue}>{item.value}</span>
                    <span className={styles.heroTrustLabel}>{item.label}</span>
                  </div>
                ))}
              </div>
            </div>
            {/* Фото удалено по требованию */}
          </div>
          {isModalOpen && (
            <div className={styles.modalOverlay} onClick={() => setIsModalOpen(false)}>
              <div className={styles.modalCard} onClick={(event) => event.stopPropagation()}>
                <button
                  type="button"
                  className={styles.modalClose}
                  onClick={() => setIsModalOpen(false)}
                >
                  ×
                </button>
                <form className={styles.modalForm} onSubmit={handleSubmit}>
                  <h2 className={styles.modalTitle}>Получить консультацию</h2>
                  <input
                    className={styles.modalInput}
                    name="name"
                    type="text"
                    placeholder="Ваше имя"
                    required
                  />
                  <input
                    className={styles.modalInput}
                    name="phone"
                    type="tel"
                    placeholder="Телефон"
                    required
                  />
                  <textarea
                    className={styles.modalTextarea}
                    name="message"
                    placeholder="Комментарий (необязательно)"
                  />
                  <button
                    className={styles.modalSubmit}
                    type="submit"
                    disabled={isSending}
                  >
                    {isSending ? "Отправка..." : "Отправить"}
                  </button>
                  {formStatus !== "idle" && (
                    <p className={formStatus === "success" ? styles.modalSuccess : styles.modalError}>
                      {formMessage}
                    </p>
                  )}
                </form>
              </div>
            </div>
          )}
        </div>
      </header>
    </div>
  );
}
