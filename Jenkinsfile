pipeline {
    agent any
    environment {
        DOCKER_COMPOSE = 'docker-compose'
        APP_NAME = 'laravel-app'
    }
    
    stages {
        stage('Checkout') {
            steps {
                checkout scm
            }
        }
        
        stage('Build') {
            steps {
                sh """
                    ${DOCKER_COMPOSE} build
                    ${DOCKER_COMPOSE} run --rm app composer install --no-dev
                    ${DOCKER_COMPOSE} run --rm app php artisan key:generate
                """
            }
        }
        
        stage('Test') {
            steps {
                sh "${DOCKER_COMPOSE} run --rm app php artisan test"
            }
        }
        
        stage('Database') {
            steps {
                sh "${DOCKER_COMPOSE} run --rm app php artisan migrate --force"
            }
        }
        
        stage('Deploy') {
            steps {
                sh """
                    ${DOCKER_COMPOSE} down
                    ${DOCKER_COMPOSE} up -d
                    ${DOCKER_COMPOSE} run --rm app php artisan cache:clear
                    ${DOCKER_COMPOSE} run --rm app php artisan config:cache
                """
            }
        }
    }
    
    post {
        failure {
            sh "${DOCKER_COMPOSE} down"
            mail to: 'admin@example.com',
                 subject: "Failed Pipeline: ${env.JOB_NAME}",
                 body: "Pipeline failure: ${env.BUILD_URL}"
        }
    }
}