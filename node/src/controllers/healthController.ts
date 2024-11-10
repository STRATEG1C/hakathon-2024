import { Router } from 'express';
import { getHealthCheck } from '../services/healthService';

const router = Router();

router.get('/healthz', async (_req, res) => {
  await getHealthCheck();
  res.setHeader('Content-Type', 'application/json');
  res.end(JSON.stringify({ status: 'OK' }));
});

export { router as healthController };
