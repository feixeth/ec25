pipeline {
    agent any
    environment {
        DDEV_PROJECT = 'ec25'
        COMPOSE_PROJECT_NAME = "ec_prod"
        COMPOSE_HTTP_TIMEOUT = '180' 
    }

    stages {
        stage('Build') {
            steps {
                sh '''
                    docker-compose build
                    docker-compose run --rm app composer install --no-dev
                    docker-compose run --rm app php artisan key:generate
                    docker-compose run --rm app npm install
                    docker-compose run --rm app npm run build
                '''
            }
        }

        stage('Test') {
            steps {
                sh 'docker-compose run --rm app php artisan test'
            }
        }

        stage('Deploy') {
            steps {
                sh '''
                    docker-compose down
                    docker-compose up -d
                    docker-compose run --rm app php artisan migrate --force
                    docker-compose run --rm app php artisan config:cache
                    docker-compose run --rm app php artisan route:cache
                    docker-compose run --rm app php artisan view:cache
                '''
            }
        }
    }

    post {
        failure {
            sh 'docker-compose down'
        }
    }
}
