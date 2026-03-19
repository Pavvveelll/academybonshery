"use client";

import { FormEvent, useEffect, useState } from "react";
import styles from "./Header.module.css";
import { BrandLogoSvg } from "./BrandLogoSvg";

const navItems = [
  { label: "Основной курс", href: "#course" },
  { label: "Форматы", href: "#formats" },
  { label: "Ближайшие группы", href: "#enrollment" },
  { label: "FAQ", href: "#faq" },
  { label: "Контакты", href: "#contacts" },
];

const tickerItems = [
  { label: "Практика с первого дня", href: "#course" },
  { label: "Поддержка после выпуска", href: "#contacts" },
  { label: "Группы до 6 человек", href: "#enrollment" },
  { label: "Сертификат академии", href: "#graduates" },
  { label: "Гибкий график обучения", href: "#formats" },
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
        headers: {
          "Content-Type": "application/json",
        },
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
    } catch {
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
                  <p className={styles.brandSubtitle}>Академия груминга с 2009 года</p>
                </div>
              </div>

              <div className={styles.goldLine} />

              <div className={styles.bannerBottom}>
                <a className={styles.phone} href="tel:+74999940140">
                  8 (499) 994-01-40
                </a>
                <span className={styles.separator} aria-hidden="true" />
                <p className={styles.workTime}>ежедневно с 10.00 - 20.00</p>
              </div>
            </div>

            <nav className={styles.navStrip}>
              <ul className={styles.menu}>
                {navItems.map((item) => (
                  <li key={item.label}>
                    <a
                      href={item.href}
                      className={styles.tooltipTarget}
                      data-tooltip={`Перейти: ${item.label}`}
                    >
                      {item.label}
                    </a>
                  </li>
                ))}
              </ul>

              <button
                type="button"
                className={`${styles.signupButton} ${styles.tooltipTarget}`}
                data-tooltip="Открыть форму быстрой записи"
                onClick={() => setIsModalOpen(true)}
              >
                Записаться
              </button>
            </nav>
          </div>

          <div className={styles.infoTicker} aria-label="Преимущества академии">
            <div className={styles.tickerLane}>
              <div className={styles.tickerTrack}>
                {tickerItems.map((item) => (
                  <a
                    className={`${styles.tickerItem} ${styles.tooltipTarget}`}
                    href={item.href}
                    key={`ticker-a-${item.label}`}
                    data-tooltip={`Перейти: ${item.label}`}
                  >
                    {item.label}
                  </a>
                ))}
              </div>
              <div className={styles.tickerTrack} aria-hidden="true">
                {tickerItems.map((item) => (
                  <a
                    className={styles.tickerItem}
                    href={item.href}
                    key={`ticker-b-${item.label}`}
                    tabIndex={-1}
                  >
                    {item.label}
                  </a>
                ))}
              </div>
            </div>
          </div>

          <div className={styles.heroStage}>
            <div className={styles.heroGlow} />
            <div className={styles.heroText}>
              <p className={styles.heroKicker}>BONSHERY GROOM</p>
              <h1 className={styles.heroTitle}>Старт ближайших потоков</h1>
              <p className={styles.heroDescription}>
                Современная академия груминга с насыщенной практикой, гибкими форматами
                обучения и поддержкой после выпуска.
              </p>
              <div className={styles.heroActions}>
                <button
                  type="button"
                  className={`${styles.heroPrimary} ${styles.tooltipTarget}`}
                  data-tooltip="Открыть форму записи на курс"
                  onClick={() => setIsModalOpen(true)}
                >
                  Записаться на курс
                </button>
                <a
                  href="#course"
                  className={`${styles.heroSecondary} ${styles.tooltipTarget}`}
                  data-tooltip="Посмотреть программы и длительность"
                >
                  Программы обучения
                </a>
              </div>
            </div>

            <div className={styles.heroMedia}>
              <img
                src="/images/master-photo.jpg"
                alt="Мастер Bonshery Groom с собакой"
                loading="lazy"
              />
            </div>
          </div>

          {isModalOpen && (
            <div className={styles.modalOverlay} onClick={() => setIsModalOpen(false)}>
              <div className={styles.modalCard} onClick={(event) => event.stopPropagation()}>
                <button
                  type="button"
                  className={styles.modalClose}
                  onClick={() => setIsModalOpen(false)}
                  aria-label="Закрыть окно"
                >
                  ×
                </button>

                <h3 className={styles.modalTitle}>Запись на курс</h3>
                <p className={styles.modalText}>
                  Оставьте контакты, и мы перезвоним вам в ближайшее время.
                </p>

                <form className={styles.modalForm} onSubmit={handleSubmit}>
                  <label className={styles.modalField}>
                    <span>Имя</span>
                    <input name="name" type="text" placeholder="Ваше имя" required />
                  </label>

                  <label className={styles.modalField}>
                    <span>Телефон</span>
                    <input name="phone" type="tel" placeholder="+7 (___) ___-__-__" required />
                  </label>

                  <label className={styles.modalField}>
                    <span>Комментарий</span>
                    <textarea name="message" rows={4} placeholder="Например: интересует интенсив" />
                  </label>

                  <button className={styles.modalSubmit} type="submit" disabled={isSending}>
                    {isSending ? "Отправка..." : "Перезвоните мне"}
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
