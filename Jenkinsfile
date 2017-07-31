pipeline {
    agent any
    environment {
        ENCRYPTED = credentials('magento2gdpr')
    }
    stages {
        stage('Build') {
            steps {
                wrap([$class: 'AnsiColorBuildWrapper', 'colorMapName': 'XTerm']) {
                    sh 'bin/decrypt.sh && DEPLOY_ENVIRONMENT=Build bin/install.sh && bin/build.sh'
                }
                archiveArtifacts artifacts: 'build/*'
            }
        }
        stage('Test') {
            steps {
                wrap([$class: 'AnsiColorBuildWrapper', 'colorMapName': 'XTerm']) {
                    sh 'bin/test.sh'
                }
                junit 'build/test.xml'
                publishHTML([
                    allowMissing: true,
                    alwaysLinkToLastBuild: true,
                    keepAll: false,
                    reportDir: 'build/coverage',
                    reportFiles: 'index.html', 
                    reportName: 'Coverage',
                    reportTitles: ''
                ])
            }
        }
        stage('Deploy') {
            steps {
                wrap([$class: 'AnsiColorBuildWrapper', 'colorMapName': 'XTerm']) {
                    sh 'bin/deploy.sh Continuous'
                }
            }
        }
    }
    post {
        success {
            slackSend  channel: '#build', color: '#00FF00', message: "${env.JOB_NAME} - #${env.BUILD_NUMBER} Success after (${env.BUILD_URL})"
        }
        failure {
            slackSend  channel: '#build', color: '#FF0000', message: "${env.JOB_NAME} - #${env.BUILD_NUMBER} Failure after (${env.BUILD_URL})"
        }
    }
}