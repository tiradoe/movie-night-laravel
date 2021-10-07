FROM node:lts-alpine

# Install python/pip
ENV PYTHONUNBUFFERED=1
RUN apk add --update --no-cache python2 make g++ && ln -sf python2 /usr/bin/python

RUN python -m ensurepip
RUN pip install --no-cache --upgrade pip setuptools

# make the 'app' folder the current working directory
WORKDIR /app

# copy both 'package.json' and 'package-lock.json' (if available)
COPY package*.json ./

# install project dependencies
RUN npm install

# copy project files and folders to the current working directory (i.e. 'app' folder)
COPY . .

EXPOSE 8080
CMD [ "npm", "run", "serve" ]
