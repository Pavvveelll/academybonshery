import styles from "./Footer.module.css";
import { LogoTextSvg } from "./LogoTextSvg";

const navLinks = [
  { label: "Основной курс", href: "#course" },
  { label: "Форматы обучения", href: "#formats" },
  { label: "Ближайшие группы", href: "#enrollment" },
  { label: "Что входит в курс", href: "#includes" },
  { label: "FAQ", href: "#faq" },
  { label: "Курс выходного дня", href: "#weekend" },
  { label: "Наши выпускники", href: "#graduates" },
  { label: "Контакты", href: "#contacts" },
  { label: "Вакансии", href: "https://www.petsgroomer.ru/vacancy_rabota_grumerom/" },
  { label: "Сведения об организации", href: "https://www.petsgroomer.ru/sveden/" },
];

const socialLinks = [
  {
    label: "ВКонтакте",
    href: "https://vk.com/club_bonshery",
    icon: (
      <svg width="22" height="22" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
        <path d="M12.785 16.241s.288-.032.436-.194c.136-.148.132-.427.132-.427s-.02-1.304.586-1.496c.598-.19 1.365 1.26 2.179 1.815.615.42 1.082.328 1.082.328l2.175-.03s1.137-.071.598-1.105c-.044-.083-.314-.663-1.618-1.875-1.366-1.268-1.183-1.063.462-3.256.999-1.338 1.4-2.152 1.274-2.501-.12-.332-.854-.244-.854-.244l-2.447.015s-.182-.025-.316.059c-.132.082-.217.272-.217.272s-.387 1.098-.904 2.031c-1.088 1.874-1.523 1.972-1.701 1.857-.413-.27-.31-1.08-.31-1.656 0-1.8.272-2.55-.529-2.744-.265-.064-.46-.106-1.138-.113-.87-.009-1.606.003-2.021.209-.277.136-.491.44-.361.457.162.022.529.1.724.369.25.344.241 1.116.241 1.116s.144 2.12-.335 2.382c-.328.179-.778-.186-1.744-1.853-.495-.907-.87-1.912-.87-1.912s-.072-.183-.202-.282c-.157-.12-.376-.158-.376-.158l-2.324.015s-.349.01-.477.164c-.114.137-.009.42-.009.42s1.819 4.27 3.877 6.421c1.888 1.973 4.031 1.842 4.031 1.842h.972z" />
      </svg>
    ),
  },
  {
    label: "Instagram",
    href: "https://www.instagram.com/bonshery_groom/",
    icon: (
      <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="1.8" strokeLinecap="round" strokeLinejoin="round" aria-hidden="true">
        <rect x="2" y="2" width="20" height="20" rx="5" />
        <circle cx="12" cy="12" r="4" />
        <circle cx="17.5" cy="6.5" r="1" fill="currentColor" stroke="none" />
      </svg>
    ),
  },
  {
    label: "Одноклассники",
    href: "https://ok.ru/group/54986486186225/",
    icon: (
      <svg width="22" height="22" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
        <path d="M12 12.75c2.9 0 5.25-2.35 5.25-5.25S14.9 2.25 12 2.25 6.75 4.6 6.75 7.5 9.1 12.75 12 12.75zm0-8.5c1.79 0 3.25 1.46 3.25 3.25S13.79 10.75 12 10.75 8.75 9.29 8.75 7.5 10.21 4.25 12 4.25zm4.21 10.95a9.7 9.7 0 0 1-2.71.76l2.77 2.77a1 1 0 0 1-1.41 1.41L12 17.27l-2.86 2.87a1 1 0 1 1-1.41-1.41l2.77-2.77a9.7 9.7 0 0 1-2.71-.76 1 1 0 1 1 .82-1.82C9.71 14.11 10.84 14.5 12 14.5s2.29-.39 3.39-.87a1 1 0 1 1 .82 1.82v-.25z" />
      </svg>
    ),
  },
  {
    label: "Facebook",
    href: "https://www.facebook.com/BonsheryAcademy/",
    icon: (
      <svg width="22" height="22" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
        <path d="M18 2h-3a5 5 0 0 0-5 5v3H7v4h3v8h4v-8h3l1-4h-4V7a1 1 0 0 1 1-1h3z" />
      </svg>
    ),
  },
];

export function Footer() {
  return (
    <footer className={styles.footer}>
      <div className={styles.glow} aria-hidden="true" />

      <div className={styles.inner}>
        {/* Колонка 1 — бренд и контакты */}
        <div className={styles.brand}>
          <div className={styles.brandCluster}>
              <LogoTextSvg className={styles.logoText} />
              <p className={styles.brandSub}>Академия груминга с 2009 года</p>
          </div>

          <ul className={styles.contacts}>
            <li>
              <span className={styles.contactIcon} aria-hidden="true">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2" strokeLinecap="round" strokeLinejoin="round">
                  <path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6A19.79 19.79 0 0 1 2.12 4.18 2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72c.127.96.361 1.903.7 2.81a2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45c.907.339 1.85.573 2.81.7A2 2 0 0 1 22 16.92z" />
                </svg>
              </span>
              <a
                href="tel:+74999940140"
                className={`${styles.contactLink} ${styles.tooltipTarget}`}
                data-tooltip="Позвонить в академию"
              >
                8 (499) 994-01-40
              </a>
            </li>
            <li>
              <span className={styles.contactIcon} aria-hidden="true">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2" strokeLinecap="round" strokeLinejoin="round">
                  <path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6A19.79 19.79 0 0 1 2.12 4.18 2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72c.127.96.361 1.903.7 2.81a2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45c.907.339 1.85.573 2.81.7A2 2 0 0 1 22 16.92z" />
                </svg>
              </span>
              <div>
                <a
                  href="tel:+79258899963"
                  className={`${styles.contactLink} ${styles.tooltipTarget}`}
                  data-tooltip="Viber / WhatsApp / Telegram"
                >
                  8 (925) 889-99-63
                </a>
                <span className={styles.contactHint}>&nbsp;Viber · WhatsApp · Telegram</span>
              </div>
            </li>
            <li>
              <span className={styles.contactIcon} aria-hidden="true">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2" strokeLinecap="round" strokeLinejoin="round">
                  <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z" />
                  <polyline points="22,6 12,13 2,6" />
                </svg>
              </span>
              <a
                href="mailto:school@petsgroomer.ru"
                className={`${styles.contactLink} ${styles.tooltipTarget}`}
                data-tooltip="Написать на e-mail"
              >
                school@petsgroomer.ru
              </a>
            </li>
            <li>
              <span className={styles.contactIcon} aria-hidden="true">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2" strokeLinecap="round" strokeLinejoin="round">
                  <circle cx="12" cy="12" r="10" />
                  <polyline points="12 6 12 12 16 14" />
                </svg>
              </span>
              <span className={styles.contactText}>Ежедневно с 10:00 до 20:00</span>
            </li>
          </ul>
        </div>

        {/* Колонка 2 — навигация */}
        <nav className={styles.nav} aria-label="Разделы академии">
          <p className={styles.colTitle}>Разделы</p>
          <ul className={styles.navList}>
            {navLinks.map((link) => (
              <li key={link.href}>
                <a
                  href={link.href}
                  className={`${styles.navLink} ${styles.tooltipTarget}`}
                  data-tooltip={`Перейти: ${link.label}`}
                  {...(link.href.startsWith("http") ? { target: "_blank", rel: "noopener noreferrer" } : {})}
                >
                  {link.label}
                </a>
              </li>
            ))}
          </ul>
        </nav>

        {/* Колонка 3 — соцсети */}
        <div className={styles.social}>
          <p className={styles.colTitle}>Мы в социальных сетях</p>
          <ul className={styles.socialList}>
            {socialLinks.map((s) => (
              <li key={s.href}>
                <a
                  href={s.href}
                  className={`${styles.socialLink} ${styles.tooltipTarget}`}
                  data-tooltip={`Открыть: ${s.label}`}
                  target="_blank"
                  rel="noopener noreferrer"
                  aria-label={s.label}
                >
                  {s.icon}
                  <span>{s.label}</span>
                </a>
              </li>
            ))}
          </ul>

          <div className={styles.payments}>
            <p className={styles.payTitle}>Принимаем к оплате</p>
            <div className={styles.payBadges}>
              <span className={`${styles.payBadge} ${styles.tooltipTarget}`} data-tooltip="Банковская карта">VISA</span>
              <span className={`${styles.payBadge} ${styles.tooltipTarget}`} data-tooltip="Банковская карта">Mastercard</span>
              <span className={`${styles.payBadge} ${styles.tooltipTarget}`} data-tooltip="Национальная платёжная система">МИР</span>
            </div>
          </div>
        </div>
      </div>

      <div className={styles.bottom}>
        <div className={styles.bottomInner}>
          <p className={styles.copy}>© 2009–{new Date().getFullYear()} Академия груминга Bonshery Groom. Все права защищены.</p>
          <a href="https://www.petsgroomer.ru" className={styles.siteLink} target="_blank" rel="noopener noreferrer">petsgroomer.ru</a>
        </div>
      </div>
    </footer>
  );
}
