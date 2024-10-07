pipeline {
    agent any
    stages {
        stage('Build') {
            steps {
                script {
                    sh 'docker-compose up --build -d'
                }
            }
        }
        stage('Test') {
            steps {
                script {
                    sh 'make unit-test'
                }
            }
        }
        stage('Deploy') {
            steps {
                script {
                    sh 'docker-compose down && docker-compose up -d'
                }
            }
        }
    }
    post {
        success {
            echo 'Build succeeded!'
        }
        failure {
            echo 'Build failed!'
            // Notify the team?
        }
    }
}
