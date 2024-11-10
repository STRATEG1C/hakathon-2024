import { Router } from 'express';
import { getHeathCheck } from '../services/healthService';

const router = Router();

router.get('/healthz', async (_req, res) => {
  await getHeathCheck();
  res.setHeader('Content-Type', 'application/json');
  res.end(JSON.stringify({ status: 'OK' }));
});

export { router as healthController };
