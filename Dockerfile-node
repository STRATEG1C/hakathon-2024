FROM node:23-alpine as node-build

WORKDIR /app

COPY ./node/package.json .

RUN npm install

COPY ./node .

RUN npm run build

EXPOSE 3000

CMD ["npm", "run", "start"]
