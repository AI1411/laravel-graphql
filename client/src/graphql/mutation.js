import gql from 'graphql-tag';

export const CREATE_ACCOUNT = gql`
    mutation (
        $name: String!
        $twitter_id: String!
        $email: String!
        $password: String!
        $password_confirmation: String!
    ) {
        CreateAccount(
            name: $name
            twitter_id: $twitter_id
            email: $email
            password: $password
            password_confirmation: $password_confirmation
        ) {
            account {
                twitter_id
            }
            token {
                access_token
                token_type
                expires_in
            }
        }
    }
`;
