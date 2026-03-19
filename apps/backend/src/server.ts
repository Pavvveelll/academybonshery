import 'dotenv/config';
import express from 'express';
import cors from 'cors';

const app = express();
const port = Number(process.env.PORT ?? 4000);

app.use(cors({ origin: process.env.FRONTEND_ORIGIN ?? 'http://localhost:5173' }));
app.use(express.json());

app.get('/api/health', (_req, res) => {
  res.json({ ok: true, service: 'petsgroomer-api' });
});

app.get('/api/pages/:slug', (req, res) => {
  res.json({
    slug: req.params.slug,
    title: 'Stub page',
    content: 'Здесь будет контент, перенесенный из legacy CMS.'
  });
});

app.listen(port, () => {
  // Keep startup logs explicit for operational checks in dev/prod.
  console.log(`API started on http://localhost:${port}`);
});
