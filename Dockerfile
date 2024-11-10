# Step 1 - Build and launch
FROM node:22-alpine as node-build
WORKDIR /app
COPY ./node/package.json .
RUN npm install
COPY ./node .
RUN npm run build
EXPOSE 8080
CMD ["npm", "run", "start"]

