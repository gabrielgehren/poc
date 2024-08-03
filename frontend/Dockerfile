# Use a imagem oficial do Node.js
FROM node:20

# Defina o diretório de trabalho
WORKDIR /app

# Copie o package.json e o package-lock.json
COPY package*.json ./

# Instale as dependências
RUN npm install

# Copie o código fonte para o contêiner
COPY . .

# Construa a aplicação para produção
#RUN npm run build

# Exponha a porta 8080
EXPOSE 8080

# Comando para iniciar a aplicação
CMD ["npm", "run", "dev"]
