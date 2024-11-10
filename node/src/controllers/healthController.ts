import { Router } from 'express';
import { getHeathCheck } from '../services/healthService';

const router = Router();

router.get('/healthz', async (_req, res) => {
  await getHeathCheck();
  res.json({ status: 'OK' });
});

export { router as healthController };
