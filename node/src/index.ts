import process from 'node:process';
import dotenv from 'dotenv';
import express from 'express';
import { healthController } from './controllers/healthController';

// Setup
dotenv.config();

const app = express();
const port = process.env.PORT || 3000;

// Controllers are here
app.use('/', healthController);

// Launch
app.listen(port, () => {
  console.log(`[server]: Server is running at http://localhost:${port}`);
});
