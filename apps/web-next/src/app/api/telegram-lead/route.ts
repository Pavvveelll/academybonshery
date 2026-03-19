import { NextResponse } from "next/server";

type LeadPayload = {
  name?: string;
  phone?: string;
  message?: string;
};

function sanitize(input: string): string {
  return input
    .replace(/&/g, "&amp;")
    .replace(/</g, "&lt;")
    .replace(/>/g, "&gt;")
    .replace(/\"/g, "&quot;")
    .replace(/'/g, "&#039;");
}

export async function POST(request: Request) {
  const token = process.env.TELEGRAM_BOT_TOKEN;
  const chatId = process.env.TELEGRAM_CHAT_ID;

  if (!token || !chatId) {
    return NextResponse.json(
      { ok: false, error: "Telegram config is missing" },
      { status: 500 },
    );
  }

  let payload: LeadPayload;

  try {
    payload = (await request.json()) as LeadPayload;
  } catch {
    return NextResponse.json({ ok: false, error: "Invalid JSON" }, { status: 400 });
  }

  const name = (payload.name ?? "").trim();
  const phone = (payload.phone ?? "").trim();
  const message = (payload.message ?? "").trim();

  if (!phone) {
    return NextResponse.json(
      { ok: false, error: "Phone is required" },
      { status: 400 },
    );
  }

  const text = [
    "<b>Новая заявка с сайта Bonshery Groom</b>",
    `Имя: ${sanitize(name || "Не указано")}`,
    `Телефон: ${sanitize(phone)}`,
    `Сообщение: ${sanitize(message || "Не указано")}`,
  ].join("\n");

  const telegramResponse = await fetch(`https://api.telegram.org/bot${token}/sendMessage`, {
    method: "POST",
    headers: {
      "Content-Type": "application/json",
    },
    body: JSON.stringify({
      chat_id: chatId,
      text,
      parse_mode: "HTML",
    }),
    cache: "no-store",
  });

  if (!telegramResponse.ok) {
    return NextResponse.json(
      { ok: false, error: "Telegram API request failed" },
      { status: 502 },
    );
  }

  return NextResponse.json({ ok: true });
}
