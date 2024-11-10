# Step 1 - Build apps
FROM node:22-alpine as node-build
WORKDIR /app
COPY ./node/package.json .
RUN npm install
COPY ./node .
RUN npm run build

# Step 2 - Server with Nginx
FROM nginx:1.27.1-alpine
WORKDIR /usr/share/nginx/html
RUN rm -rf *
COPY --from=node-build /app/dist .
EXPOSE 80
ENTRYPOINT [ "nginx", "-g", "daemon off;" ]

