FROM node:lts-alpine

# Install python/pip
RUN apk add --update --no-cache g++ make python3 py3-pip

# make the 'app' folder the current working directory
WORKDIR /app

# copy both 'package.json' and 'package-lock.json' (if available)
COPY package*.json ./

# install project dependencies
RUN npm install --verbose

# copy project files and folders to the current working directory (i.e. 'app' folder)
COPY . .

EXPOSE 8080
CMD [ "npm", "run", "serve" ]
