import { Client } from "basic-ftp";
import { config as loadEnv } from "dotenv";
import fs from "node:fs";
import path from "node:path";

loadEnv({ path: ".env.deploy" });

const required = ["FTP_HOST", "FTP_USER", "FTP_PASSWORD", "FTP_REMOTE_DIR"];
const missing = required.filter((name) => !process.env[name]);

if (missing.length > 0) {
  console.error(`Missing env vars in .env.deploy: ${missing.join(", ")}`);
  process.exit(1);
}

const localDir = process.env.FTP_LOCAL_DIR || "apps/web-next/out";
const localPath = path.resolve(process.cwd(), localDir);

if (!fs.existsSync(localPath)) {
  console.error(`Local directory not found: ${localPath}`);
  console.error("Run `npm run build:web` first to generate static files.");
  process.exit(1);
}

const client = new Client(30000);
client.ftp.verbose = false;

try {
  await client.access({
    host: process.env.FTP_HOST,
    user: process.env.FTP_USER,
    password: process.env.FTP_PASSWORD,
    secure: process.env.FTP_SECURE === "true",
  });

  await client.ensureDir(process.env.FTP_REMOTE_DIR);
  await client.clearWorkingDir();
  await client.uploadFromDir(localPath);

  console.log(`Uploaded ${localDir} -> ${process.env.FTP_REMOTE_DIR}`);
} catch (error) {
  console.error("FTP deploy failed:", error);
  process.exitCode = 1;
} finally {
  client.close();
}
